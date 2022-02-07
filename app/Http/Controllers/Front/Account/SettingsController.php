<?php

namespace App\Http\Controllers\Front\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountSettings\UpdateImageProfileRequest;
use App\Http\Requests\AccountSettings\UpdateUserPasswordRequest;
use App\Http\Requests\AccountSettings\UpdateUserRequest;
use App\Services\StoreImageService;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $user = Auth::user();
        return view('front.account.settings', compact('user'));
    }

    public function update(UpdateUserRequest $request)
    {
        $request->user()->update($request->validated());

        return redirect()->route('account.settings.index');
    }

    public function updatePassword(UpdateUserPasswordRequest $request)
    {
        $validated = $request->validated();
        $request->user()->setPassword($validated['password']);
        $request->user()->save();

        return redirect()->route('account.settings.index');
    }

    public function updateImageProfile(UpdateImageProfileRequest $request, StoreImageService $storeImageService)
    {
        $validated = $request->validated();
        $path = $storeImageService->store($validated['preview_image']);
        $request->user()->update(['preview_image' => $path]);

        return response()->json([
            'url' => $storeImageService->url($path),
        ]);
    }
}
