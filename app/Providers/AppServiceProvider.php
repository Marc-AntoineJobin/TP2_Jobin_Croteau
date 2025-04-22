<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\FilmRepositoryInterface;
use App\Repository\Eloquent\FilmRepository;
use App\Repository\CriticRepositoryInterface;
use App\Repository\Eloquent\CriticRepository;
use App\Repository\UserRepositoryInterface;
use App\Repository\Eloquent\UserRepository;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\RepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FilmRepositoryInterface::class, FilmRepository::class);
        $this->app->bind(CriticRepositoryInterface::class, CriticRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
