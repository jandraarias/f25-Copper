<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\User;
use App\Models\ItineraryInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ItineraryInvitationMail;

class ItineraryController extends Controller
{
    /** Display a list of itineraries (owned + collaborative) */
    public function index()
    {
        $traveler = Auth::user()->traveler;

        $itineraries = Itinerary::query()
            ->where('traveler_id', $traveler->id)
            ->orWhereHas('collaborators', fn($q) => $q->where('user_id', Auth::id()))
            ->with(['collaborators', 'countries'])
            ->latest()
            ->paginate(10);

        return view('traveler.itineraries.index', compact('itineraries'));
    }

    /** Show create form */
    public function create()
    {
        return view('traveler.itineraries.create');
    }

    /** Store a new itinerary */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'countries'        => ['required', 'array', 'min:1'],
            'countries.*'      => ['integer', 'exists:countries,id'],
            'destination'      => ['nullable', 'string', 'max:255'],
            'start_date'       => ['nullable', 'date'],
            'end_date'         => ['nullable', 'date', 'after_or_equal:start_date'],
            'description'      => ['nullable', 'string'],
            'is_collaborative' => ['nullable', 'boolean'],
            'invite_emails'    => ['nullable', 'array'],
            'invite_emails.*'  => ['email', 'distinct'],
        ]);

        $traveler = Auth::user()->traveler;

        $itinerary = $traveler->itineraries()->create([
            'name'             => $validated['name'],
            'destination'      => $validated['destination'] ?? null,
            'start_date'       => $validated['start_date'] ?? null,
            'end_date'         => $validated['end_date'] ?? null,
            'description'      => $validated['description'] ?? null,
            'is_collaborative' => $request->boolean('is_collaborative'),
        ]);

        $itinerary->countries()->attach($validated['countries']);

        if ($itinerary->is_collaborative) {
            $this->processInvitations($itinerary, $validated['invite_emails'] ?? []);
        }

        return redirect()
            ->route('traveler.itineraries.index')
            ->with('success', 'Itinerary created successfully!');
    }

    /** Show a specific itinerary */
    public function show(Itinerary $itinerary)
    {
        $this->authorize('view', $itinerary);
        $itinerary->load(['items', 'countries', 'collaborators', 'invitations']);

        return view('traveler.itineraries.show', compact('itinerary'));
    }

    /** Show edit form */
    public function edit(Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        $itinerary->load(['countries', 'collaborators', 'invitations']);

        $existingCollaborators = $itinerary->collaborators->pluck('email')->toArray();
        $pendingInvites = $itinerary->invitations->pluck('email')->toArray();

        return view('traveler.itineraries.edit', compact('itinerary', 'existingCollaborators', 'pendingInvites'));
    }

    /** Update itinerary */
    public function update(Request $request, Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'countries'        => ['required', 'array', 'min:1'],
            'countries.*'      => ['integer', 'exists:countries,id'],
            'destination'      => ['nullable', 'string', 'max:255'],
            'start_date'       => ['nullable', 'date'],
            'end_date'         => ['nullable', 'date', 'after_or_equal:start_date'],
            'description'      => ['nullable', 'string'],
            'is_collaborative' => ['nullable', 'boolean'],
            'invite_emails'    => ['nullable', 'array'],
            'invite_emails.*'  => ['email', 'distinct'],
        ]);

        $itinerary->update([
            'name'             => $validated['name'],
            'destination'      => $validated['destination'] ?? null,
            'start_date'       => $validated['start_date'] ?? null,
            'end_date'         => $validated['end_date'] ?? null,
            'description'      => $validated['description'] ?? null,
            'is_collaborative' => $request->boolean('is_collaborative'),
        ]);

        $itinerary->countries()->sync($validated['countries']);

        if (!$itinerary->is_collaborative) {
            $itinerary->collaborators()->detach();
            $itinerary->invitations()->delete();
        } else {
            $this->processInvitations($itinerary, $validated['invite_emails'] ?? []);
        }

        return redirect()
            ->route('traveler.itineraries.index')
            ->with('success', 'Itinerary updated successfully!');
    }

    /** Delete itinerary (creator only) */
    public function destroy(Itinerary $itinerary)
    {
        $this->authorize('delete', $itinerary);

        $itinerary->countries()->detach();
        $itinerary->collaborators()->detach();
        $itinerary->invitations()->delete();
        $itinerary->delete();

        return redirect()
            ->route('traveler.itineraries.index')
            ->with('success', 'Itinerary deleted successfully!');
    }

    /** Direct invite from edit page form */
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

    /**
     * Handle collaborator invitations (shared for create & update).
     */
    protected function processInvitations(Itinerary $itinerary, array $emails)
    {
        $emails = collect($emails)
            ->map(fn($e) => strtolower(trim($e)))
            ->filter(fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
            ->unique();

        foreach ($emails as $email) {
            $user = User::where('email', $email)->first();

            if ($user) {
                // If user exists and not already collaborator, attach them
                if (!$itinerary->collaborators()->where('user_id', $user->id)->exists()) {
                    $itinerary->collaborators()->attach($user->id);
                }
            } else {
                // If not a user, create or update invitation
                $invitation = ItineraryInvitation::updateOrCreate(
                    ['itinerary_id' => $itinerary->id, 'email' => $email],
                    [
                        'status' => 'pending',
                        'token' => Str::uuid()->toString(),
                    ]
                );

                // Send invitation email
                Mail::to($email)->send(new ItineraryInvitationMail($invitation));
            }
        }
    }
}
