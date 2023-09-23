<?php

namespace App\Providers;

use App\Enums\CommentStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Models\{Auction, Comment, Order, Page, User};
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
            $ordersCount = Order::count();

            $view->with(compact('notificationsCount', 'commentsCount', 'ordersCount'));
        });

        view()->composer(['back.menus.index', 'back.slides.create', 'back.slides.edit','back.links.create', 'back.links.edit'], function ($view) {

            $pages = Page::pluck('slug');

            $view->with('pages', $pages);
        });

        view()->composer(['back.index'], function ($view) {
            $usersCount = User::count();
            $auctionsCount = Auction::count();
            $ordersCount = Order::count();
            $totalSell = Order::where('status', OrderStatusEnum::paid)->sum('discount_price');

            $view->with(compact('usersCount', 'auctionsCount', 'ordersCount', 'totalSell'));
        });
    }
}
