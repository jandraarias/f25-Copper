<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\Expert;
use App\Models\ExpertItineraryInvitation;
use App\Models\User;
use App\Models\ItineraryInvitation;
use App\Services\ItineraryGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ItineraryInvitationMail;

class ItineraryController extends Controller
{
    public function __construct(
        protected ItineraryGenerationService $generationService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $traveler = Auth::user()->traveler;

        $query = Itinerary::query()
            ->where('traveler_id', $traveler->id)
            ->orWhereHas('collaborators', fn($q) => $q->where('user_id', Auth::id()))
            ->with(['collaborators', 'countries'])
            ->orderBy('start_date', 'asc');

        // search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // filter (upcoming/past/all)
        if ($filter = $request->get('filter')) {
            if ($filter === 'upcoming') {
                $query->whereDate('start_date', '>=', today());
            }
            if ($filter === 'past') {
                $query->whereDate('end_date', '<', today());
            }
        }

        $itineraries = $query->paginate(10)->withQueryString();

        return view('traveler.itineraries.index', compact('itineraries'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE / STORE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $experts = Expert::with('user', 'reviews')
            ->select('id', 'name', 'city', 'expertise', 'languages', 'experience_years', 'hourly_rate', 'availability', 'user_id')
            ->get();
        
        return view('traveler.itineraries.create', compact('experts'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateItinerary($request);

        $traveler = Auth::user()->traveler;

        $itinerary = $traveler->itineraries()->create([
            'name'                  => $validated['name'],
            'destination'           => $validated['destination'] ?? null,
            'location'              => $validated['location'] ?? null,
            'preference_profile_id' => $validated['preference_profile_id'] ?? null,
            'start_date'            => $validated['start_date'] ?? null,
            'end_date'              => $validated['end_date'] ?? null,
            'description'           => $validated['description'] ?? null,
            'is_collaborative'      => $request->boolean('is_collaborative'),
        ]);

        $itinerary->countries()->attach($validated['countries']);

        if ($itinerary->is_collaborative) {
            $this->processInvitations($itinerary, $validated['invite_emails'] ?? []);
        }

        // Handle expert invitations
        if ($request->boolean('invite_experts') && !empty($validated['expert_ids'])) {
            $this->inviteExperts($itinerary, $validated['expert_ids']);
        }

        $result = $this->generationService->generateForItinerary($itinerary);

        if (!$result['ok']) {
            return redirect()
                ->route('traveler.itineraries.show', $itinerary)
                ->with('warning', "Itinerary created, but generation failed: {$result['error']}");
        }

        return redirect()
            ->route('traveler.itineraries.show', $itinerary)
            ->with('success', "Itinerary created and {$result['created_count']} items generated successfully!");
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(Itinerary $itinerary)
    {
        $this->authorize('view', $itinerary);

        $itinerary->load(['items.place', 'countries', 'collaborators', 'invitations']);

        return view('traveler.itineraries.show', [
            'itinerary' => $itinerary,
            'isPublicView' => true
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT / UPDATE
    |--------------------------------------------------------------------------
    */
    public function edit(Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        $itinerary->load(['countries', 'collaborators', 'invitations']);

        return view('traveler.itineraries.edit', [
            'itinerary'             => $itinerary,
            'existingCollaborators' => $itinerary->collaborators->pluck('email')->toArray(),
            'pendingInvites'        => $itinerary->invitations->pluck('email')->toArray(),
            'preferenceProfiles'    => Auth::user()->traveler->preferenceProfiles()->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);
    
        $validated = $this->validateItinerary($request);
    
        // Update only real DB columns (same pattern as store())
        $itinerary->update([
            'name'                  => $validated['name'],
            'destination'           => $validated['destination'] ?? null,
            'location'              => $validated['location'] ?? null,
            'preference_profile_id' => $validated['preference_profile_id'] ?? null,
            'start_date'            => $validated['start_date'] ?? null,
            'end_date'              => $validated['end_date'] ?? null,
            'description'           => $validated['description'] ?? null,
            'is_collaborative'      => $request->boolean('is_collaborative'),
        ]);
    
        // Update related countries
        $itinerary->countries()->sync($validated['countries']);
    
        // Handle collaborators / invitations based on collaboration flag
        if (!$itinerary->is_collaborative) {
            $itinerary->collaborators()->detach();
            $itinerary->invitations()->delete();
        } else {
            $this->processInvitations($itinerary, $validated['invite_emails'] ?? []);
        }
    
        return redirect()
            ->route('traveler.itineraries.show', $itinerary)
            ->with('success', 'Itinerary updated successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(Itinerary $itinerary)
    {
        $this->authorize('delete', $itinerary);

        $itinerary->countries()->detach();
        $itinerary->collaborators()->detach();
        $itinerary->invitations()->delete();
        $itinerary->items()->delete();
        $itinerary->delete();

        return redirect()
            ->route('traveler.itineraries.index')
            ->with('success', 'Itinerary deleted successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | REGENERATE
    |--------------------------------------------------------------------------
    */
    public function generate(Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        $itinerary->items()->delete();

        if (!$itinerary->preference_profile_id || !$itinerary->location) {
            return back()->with('error', 'Cannot generate itinerary: missing city or preference profile.');
        }

        try {
            $this->generationService->generateForItinerary($itinerary);
            return back()->with('success', 'Your itinerary has been regenerated successfully!');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Failed to generate itinerary. Please try again later.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ENABLE COLLABORATION (NEW)
    |--------------------------------------------------------------------------
    */
    public function enableCollaboration(Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        if ($itinerary->is_collaborative) {
            return back()->with('info', 'Collaboration is already enabled.');
        }

        $itinerary->update(['is_collaborative' => true]);

        return back()->with('success', 'Collaboration turned on! You can now invite collaborators.');
    }

    public function disableCollaboration(Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        if (!$itinerary->is_collaborative) {
            return back()->with('info', 'Collaboration is already disabled.');
        }

        // Turn off collaboration and remove all collaborators + invites
        $itinerary->update(['is_collaborative' => false]);
        $itinerary->collaborators()->detach();
        $itinerary->invitations()->delete();

        return back()->with('success', 'Collaboration disabled. This itinerary is now private.');
    }

    /*
    |--------------------------------------------------------------------------
    | INVITE
    |--------------------------------------------------------------------------
    */
    public function invite(Request $request, Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        $request->validate(['email' => ['required', 'email', 'max:255']]);
        $email = strtolower($request->email);

        $invitation = ItineraryInvitation::updateOrCreate(
            ['itinerary_id' => $itinerary->id, 'email' => $email],
            ['status' => 'pending', 'token' => Str::uuid()->toString()]
        );

        Mail::to($email)->send(new ItineraryInvitationMail($invitation));

        return back()->with('success', 'Invitation sent successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */
    protected function validateItinerary(Request $request): array
    {
        return $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'countries'        => ['required', 'array', 'min:1'],
            'countries.*'      => ['integer', 'exists:countries,id'],
            'destination'      => ['nullable', 'string', 'max:255'],
            'location'         => ['nullable', 'string', 'max:255'],
            'preference_profile_id' => ['nullable', 'integer', 'exists:preference_profiles,id'],
            'start_date'       => ['nullable', 'date'],
            'end_date'         => ['nullable', 'date', 'after_or_equal:start_date'],
            'description'      => ['nullable', 'string'],
            'is_collaborative' => ['nullable', 'boolean'],
            'invite_emails'    => ['nullable', 'array'],
            'invite_emails.*'  => ['email', 'distinct'],
            'invite_experts'   => ['nullable', 'boolean'],
            'expert_ids'       => ['nullable', 'array'],
            'expert_ids.*'     => ['integer', 'exists:experts,id'],
        ]);
    }

    protected function processInvitations(Itinerary $itinerary, array $emails)
    {
        $emails = collect($emails)
            ->map(fn($e) => strtolower(trim($e)))
            ->filter(fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
            ->unique();

        foreach ($emails as $email) {
            $user = User::where('email', $email)->first();

            if ($user) {
                if (!$itinerary->collaborators()->where('user_id', $user->id)->exists()) {
                    $itinerary->collaborators()->attach($user->id);
                }
            } else {
                $invitation = ItineraryInvitation::updateOrCreate(
                    ['itinerary_id' => $itinerary->id, 'email' => $email],
                    ['status' => 'pending', 'token' => Str::uuid()->toString()]
                );
                Mail::to($email)->send(new ItineraryInvitationMail($invitation));
            }
        }
    }

    protected function inviteExperts(Itinerary $itinerary, array $expertIds)
    {
        $traveler = Auth::user()->traveler;

        foreach ($expertIds as $expertId) {
            $expert = Expert::find($expertId);
            
            if (!$expert) {
                continue;
            }

            // Create invitation if not already exists
            $invitation = ExpertItineraryInvitation::updateOrCreate(
                ['itinerary_id' => $itinerary->id, 'expert_id' => $expertId],
                [
                    'traveler_id' => $traveler->id,
                    'status' => 'pending'
                ]
            );

            // Invitation created; expert will see this in their dashboard in-app
        }
    }
}
