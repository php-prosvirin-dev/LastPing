<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    /**
     * @return Factory|View|\Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user()->fresh();

        $intervalSeconds =
            ($user->settings['check_in']['interval_minutes'] ?? 60) * 60;

        $lastCheckIn = $user->last_check_in_at
            ? $user->last_check_in_at->timestamp
            : null;

        return view('dashboard', [
            'intervalSeconds' => $intervalSeconds,
            'lastCheckIn' => $lastCheckIn,
        ]);
    }

    public function checkIn(Request $request)
    {
        $user = $request->user();

        $user->update([
            'last_check_in_at' => now(),
        ]);

        return response()->json([
            'last_check_in_at' => $user->last_check_in_at->timestamp,
        ]);
    }
}
