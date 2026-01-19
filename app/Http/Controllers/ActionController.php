<?php

namespace App\Http\Controllers;

use App\Actions\CheckInUser;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActionController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user()->load('checkIns');

        return view('dashboard.index', [
            'userResource' => (new UserResource($user))->toArray($request),
        ]);
    }

    public function checkIn(
        Request $request,
        CheckInUser $checkInUser
    ): JsonResponse {
        $user = $checkInUser
            ->execute($request->user())
            ->load('checkIns');

        return response()->json(
            new UserResource($user)
        );
    }
}
