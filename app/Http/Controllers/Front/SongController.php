<?php

namespace App\Http\Controllers\Front;

use App\Models\Song;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Services\SongService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Song\RateRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Song\StoreRequest;

class SongController extends Controller
{
    private $songService;

    public function __construct(SongService $songService)
    {
        $this->songService = $songService;
    }

    public function create()
    {
        $genres = Genre::orderBy('name', 'asc')->get();
        return view('front.songs.create', compact('genres'));
    }

    public function store(StoreRequest $request)
    {
        $this->songService->store($request->validated());

        return redirect()->route('account.index');
    }

    public function edit(Song $song)
    {
        $genres = Genre::orderBy('name', 'asc')->get();

        return view('front.songs.update', compact('song', 'genres'));
    }

    public function update(StoreRequest $request, Song $song)
    {
        $song = $this->songService->update($song, $request->validated());

        return redirect()->route('account.index');
    }

    public function destroy(Song $song)
    {
        $song->delete();

        return redirect()->route('account.index');
    }

    public function favourite(Song $song)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = Auth::user();

        $user->favouritesSongs()->toggle($song, false);

        return response()->json([
            'success' => 1,
            'status' => $user->inFavouritesSongs($song),
        ]);
    }

    public function rate(RateRequest $request, Song $song)
    {
        $user = Auth::user();
        $validated = $request->validated();
        $song->ratesUsers()->syncWithoutDetaching([
            $user->id => [
                'rate' => $validated['rate'],
            ]
        ]);

        return response()->json([
            'success' => 1,
        ]);
    }
}
