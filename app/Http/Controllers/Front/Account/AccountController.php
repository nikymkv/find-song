<?php

namespace App\Http\Controllers\Front\Account;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $user = Auth::user();

        $songs = $user->songs()
            ->with(['genre', 'author'])
            ->latest()
            ->paginate(9);

        return view('front.account.index', compact('songs'));
    }

    public function favouritesSongs()
    {
        $user = Auth::user();

        $favouritesSongs = $user->favouritesSongs()->paginate(1);

        return view('front.account.favourites', compact('favouritesSongs'));
    }
}
