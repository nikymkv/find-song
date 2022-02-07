<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Song;
use Illuminate\Http\Request;

class ModeratorController extends Controller
{
    public function index()
    {
        $songCount = Song::count();
        $moderatedSongCount = Song::moderated()->count();
        $notModeratedSongCount = Song::notModerated()->count();
        $songs = Song::with(['author', 'genre'])
            ->select([
                'id', 'user_id', 'genre_id', 'preview_image', 'name', 'is_moderated', 'created_at'
            ])
            ->orderBy('created_at', 'desc')
            ->orderBy('is_moderated', 'desc')
            ->paginate(10);

        return view('moderator.index', compact(
            'songs',
            'songCount',
            'moderatedSongCount',
            'notModeratedSongCount'
        ));
    }

    public function moderateSong(Request $request, Song $song)
    {
        if (!$request->isJson()) {
            return back();
        }

        $song->is_moderated = !$song->is_moderated;
        $song->save();

        return response()->json([
            'success' => 1,
            'status' => $song->is_moderated,
        ]);
    }

    public function getStatistic(Request $request)
    {
        $selectGenres = Genre::withCount('songs');
        if ($request->has('genres')) {
            $selectGenres = $selectGenres->whereIn('id', $request->get('genres', [1]));
        }
        $genres = Genre::orderBy('name', 'desc')->get();
        $selectGenres = $selectGenres->orderBy('name', 'desc')->get();
        return view('moderator.statistics', compact('genres', 'selectGenres'));
    }
}
