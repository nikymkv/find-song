<?php

namespace App\ViewComposers;

use App\Models\Genre;
use Illuminate\View\View;

class GenresComposer
{
    public function compose(View $view)
    {
        return $view->with(
            'genres',
            $this->getGenres(),
        );
    }

    public function getGenres()
    {
        return Genre::select(['id', 'name'])->orderBy('name', 'asc')->get();
    }
}
