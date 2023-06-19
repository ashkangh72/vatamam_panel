<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Filter;
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

        view()->composer(['back.partials.notifications', 'back.partials.sidebar'], function ($view) {

            $notifications = auth()->user()->unreadNotifications()->latest()->take(5)->get();

            $view->with('notifications', $notifications);
        });

//        view()->composer(['back.products.partials.filters', 'back.products.partials.index-filters'], function ($view) {
//
//            $categories = Category::where('type', 'productcat')->orderBy('ordering')->get();
//
//            $view->with('categories', $categories);
//        });
//
//        view()->composer(['back.products.categories.edit'], function ($view) {
//
//            $filters = Filter::latest()->get();
//
//            $view->with('filters', $filters);
//        });
//
//        view()->composer(['back.menus.index', 'back.sliders.create', 'back.sliders.edit', 'back.banners.create', 'back.banners.edit', 'back.links.create', 'back.links.edit'], function ($view) {
//
//            $pages = Page::pluck('slug');
//
//            $view->with('pages', $pages);
//        });
    }
}
