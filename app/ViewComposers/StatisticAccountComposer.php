<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class StatisticAccountComposer
{
    public function compose(View $view)
    {
        return $view->with(
            'accountStatistics',
            $this->getStatistics(),
        );
    }

    public function getStatistics()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = Auth::user();
        $data = collect();

        $data->put('total_songs', $user->songs()->count());
        $data->put('avg_rate', round($user->ratedSongs()->moderated()->avg('rate'), 2));
        $data->put(
            'total_views',
            $user->songs()
                ->moderated()
                ->join(
                    'view_songs',
                    'songs.id',
                    '=',
                    'view_songs.song_id'
                )
                ->select(['view_songs.id', 'songs.user_id'])
                ->count()
        );

        return $data;
    }
}
