<?php

namespace App\Providers;

use App\Error;
use App\Observers\ErrorObserver;
use App\Observers\PublishedSpellingObserver;
use App\Observers\SpellingObserver;
use App\Observers\WordObserver;
use App\PublishedSpelling;
use App\Spelling;
use App\Word;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
