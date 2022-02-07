<?php

namespace App\ViewComposers;

use App\Models\Song;
use App\Models\User;
use App\Models\RateSong;
use Illuminate\View\View;

class NewAuthorsComposer
{
    public function compose(View $view)
    {
        return $view->with(
            'newAuthors',
            $this->getNewAuthors(),
        );
    }

    public function getNewAuthors()
    {
        return User::where('is_moderator', 'false')
            ->has('songs')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
    }
}
