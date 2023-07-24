<?php

namespace App\Providers;

use App\Models\Page;
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
        // SHARE WITH SPECIFIC VIEW

        view()->composer(['back.partials.sidebar'], function ($view) {
            $notificationsCount = auth()->user()->unreadNotifications()->count();

            $view->with('notificationsCount', $notificationsCount);
        });

        view()->composer(['back.menus.index', 'back.sliders.create', 'back.sliders.edit', 'back.links.create', 'back.links.edit'], function ($view) {

            $pages = Page::pluck('slug');

            $view->with('pages', $pages);
        });
    }
}
