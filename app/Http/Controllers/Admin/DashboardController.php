<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $total = User::count();

        // Counts per role (fill missing roles with 0)
        $byRole = User::select('role')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role');

        $counts = [
            'traveler' => (int) ($byRole['traveler'] ?? 0),
            'expert'   => (int) ($byRole['expert']   ?? 0),
            'business' => (int) ($byRole['business'] ?? 0),
            'admin'    => (int) ($byRole['admin']    ?? 0),
        ];

        $percents = [];
        foreach ($counts as $role => $n) {
            $percents[$role] = $total ? number_format(($n / $total) * 100, 1) : '0.0';
        }

        $last7  = User::where('created_at', '>=', now()->subDays(7))->count();
        $last30 = User::where('created_at', '>=', now()->subDays(30))->count();

        return view('admin.dashboard', [
            'total'    => $total,
            'counts'   => $counts,
            'percents' => $percents,
            'last7'    => $last7,
            'last30'   => $last30,
        ]);
    }
}
