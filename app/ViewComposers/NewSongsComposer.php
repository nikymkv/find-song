<?php

namespace App\ViewComposers;

use App\Models\Song;
use Illuminate\View\View;

class NewSongsComposer
{
    public function compose(View $view)
    {
        return $view->with(
            'newSongs',
            $this->getNewSongs(),
        );
    }

    public function getNewSongs()
    {
        return Song::with(['author'])->moderated()->orderBy('created_at', 'desc')->limit(6)->get();
    }
}
