<?php

namespace App\Http\Controllers;

use App\Actions\CheckInUser;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActionController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        return view('dashboard.index', [
            'user' => new UserResource(
                $request->user()->fresh()
            )->toArray($request),
        ]);
    }

    /**
     * @param Request $request
     * @param CheckInUser $checkInUser
     * @return JsonResponse
     */
    public function checkIn(
        Request $request,
        CheckInUser $checkInUser
    ): JsonResponse {
        $user = $checkInUser->execute($request->user());

        return response()->json(
            new UserResource($user)
        );
    }
}
