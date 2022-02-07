<?php

namespace App\Providers;

use App\ViewComposers\GenresComposer;
use App\ViewComposers\NewSongsComposer;
use Illuminate\Support\ServiceProvider;
use App\ViewComposers\NewAuthorsComposer;
use App\ViewComposers\StatisticAccountComposer;

class ViewComposerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('front.account.index', StatisticAccountComposer::class);
        view()->composer('front.home', NewSongsComposer::class);
        view()->composer('front.home', NewAuthorsComposer::class);
        view()->composer('front.home', GenresComposer::class);
    }
}
