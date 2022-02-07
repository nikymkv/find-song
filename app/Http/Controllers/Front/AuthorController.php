<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Author\RateRequest;
use App\Models\User;

class AuthorController extends Controller
{
    public function rate(RateRequest $request, User $author)
    {
        /**
         * @var \App\Models\User $user
         */
        $authUser = Auth::user();
        $validated = $request->validated();
        $author->ratedUsers()->syncWithoutDetaching([
            $authUser->id => [
                'rate' => $validated['rate'],
            ]
        ]);

        return response()->json([
            'success' => 1,
        ]);
    }
}
