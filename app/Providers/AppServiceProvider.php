<?php

namespace App\Providers;

use App\Enums\CommentStatusEnum;
use App\Models\{Comment, Page};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->viewComposer();
    }

    private function viewComposer()
    {
        view()->composer(['back.partials.sidebar'], function ($view) {
            $notificationsCount = auth()->user()->unreadNotifications()->count();
            $commentsCount = Comment::where('status', CommentStatusEnum::pending)->count();

            $view->with(compact('notificationsCount', 'commentsCount'));
        });

        view()->composer(['back.menus.index', 'back.slides.create', 'back.slides.edit', 'back.links.create', 'back.links.edit'], function ($view) {
            $pages = Page::pluck('slug');

            $view->with('pages', $pages);
        });
    }
}
