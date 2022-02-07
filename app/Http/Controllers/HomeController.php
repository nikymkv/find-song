<?php

namespace App\Http\Controllers;

use App\Http\Requests\Song\SearchRequest;
use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $songsChart = $this->getChart($request->get('genre'), $request->get('period'));

        return view('front/home', compact('songsChart'));
    }

    public function search(SearchRequest $request)
    {
        if (! $request->isJson()) {
            return response('Not allowed');
        }

        $data = $request->validated();

        $songs = Song::with(['author' => function ($query) {
            $query->select(['id', 'name']);
        }])
            ->moderated()
            ->search($data['query'])
            ->select([
                'id',
                'name',
                'featuring_with',
                'user_id',
                'genre_id',
            ])
            ->limit(7);

        if (isset($data['genre'])) {
            $songs = $songs->where('genre_id', $data['genre']);
        }

        return response()->json($songs->get());
    }

    public function view(Song $song)
    {
        $user = Auth::user();
        $isFavouriteSong = false;
        $userRate = 0;
        if ($user) {
            $isFavouriteSong = $user->inFavouritesSongs($song);
            $userRate = $song->getRateByUser($user);
        }

        $suggestedSongs = Song::with('author')
            ->select(['id', 'user_id', 'name', 'featuring_with'])
            ->where('user_id', $song->user_id)
            ->where('id', '!=', $song->id)
            ->limit(5)
            ->get();

        return view('front/view_song', compact(
            'song', 'userRate', 'suggestedSongs', 'isFavouriteSong'
        ));
    }

    public function author(Request $request, User $user)
    {
        $rateByUser = $user->getRateByUser(Auth::user());
        $songs = $user->songs()
            ->with(['author'])
            ->join('view_songs', 'view_songs.song_id', '=', 'songs.id')
            ->select(['songs.id', DB::raw('COUNT(*) AS views_count'), 'view_songs.song_id', 'songs.user_id', 'songs.name', 'songs.preview_image'])
            ->groupBy(['songs.id', 'view_songs.song_id']);
        if ($request->has('type')) {
            $type = $request->get('type');
            if ($type == 'new') {
                $songs = $songs->orderBy('songs.created_at', 'desc');
            } else if ($type == 'all') {
                $songs = $songs;
            }
        } else {
            $songs = $songs->orderBy('views_count', 'desc');
        }

        $songs = $songs->paginate(7)->withQueryString();

        return view('front.author', compact('songs', 'user', 'rateByUser'));
    }

    private function getChart(?int $genre, ?string $period)
    {
        $songs = Song::join('rate_songs', 'rate_songs.song_id', '=', 'songs.id')
            ->with(['author'])
            ->moderated()
            ->selectRaw(
                'songs.id, ROUND(AVG(rate_songs.rate), 2) AS rate,
                songs.name, songs.genre_id, songs.preview_image, songs.user_id,
                songs.created_at'
            );

        if (isset($genre) && $genre != 0) {
            $songs->where('songs.genre_id', $genre);
        }

        if (isset($period)) {
            $currentDate = now();
            switch ($period) {
                case 'day':
                    $date = $currentDate->subDay();
                    break;
                case 'week':
                    $date = $currentDate->subWeek();
                    break;
                case 'month':
                    $date = $currentDate->subMonth();
                    break;
                default:
                    $date = $currentDate->subDay();
                    break;
            }

            $songs->where('created_at', '>=', $date);
        }

        return $songs
            ->groupBy('songs.id')
            ->orderBy('rate', 'desc')
            ->limit(10)
            ->get();
    }
}
