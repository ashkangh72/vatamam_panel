<?php

use App\Http\Controllers\Back\{AuctionController,
    CategoryController,
    CityController,
    CommentController,
    DeveloperController,
    DiscountController,
    LinkController,
    MainController,
    MenuController,
    OrderController,
    PageController,
    PermissionController,
    ProvinceController,
    RedirectController,
    RoleController,
    SearchEngineRulesController,
    SliderController,
    StatisticsController,
    TransactionController,
    UserController,
    WalletController,
    WidgetController};
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    abort(404);
});

Route::group(['as' => 'admin.', 'prefix' => 'admin/', 'middleware' => ['auth', 'Admin']], function () {

    // ------------------ MainController
    Route::get('/', [MainController::class, 'index'])->name('dashboard');

    // ------------------ users
    Route::resource('users', UserController::class);
    Route::post('users/api/index', [UserController::class, 'apiIndex'])->name('users.apiIndex');
    Route::delete('users/api/multipleDestroy', [UserController::class, 'multipleDestroy'])->name('users.multipleDestroy');
    Route::get('users/export/excel', [UserController::class, 'export'])->name('users.export');
    Route::get('users/{user}/views', [UserController::class, 'views'])->name('users.views');
    Route::get('user/profile', [UserController::class, 'showProfile'])->name('user.profile.show');#
    Route::put('user/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');#

    // ------------------ wallets
    Route::resource('wallets', WalletController::class)->only(['show']);
    Route::get('wallets/histories/{history}', [WalletController::class, 'history'])->name('wallets.history');
    Route::get('wallets/{wallet}/create', [WalletController::class, 'create'])->name('wallets.create');
    Route::post('wallets/{wallet}', [WalletController::class, 'store'])->name('wallets.store');

    // ------------------ auctions
    Route::get('auctions', [AuctionController::class, 'index'])->name('auctions.index');
    Route::post('auctions/api/index', [AuctionController::class, 'apiIndex'])->name('auctions.apiIndex');
    Route::post('auctions/accept', [AuctionController::class, 'accept'])->name('auctions.accept');
    Route::post('auctions/reject', [AuctionController::class, 'reject'])->name('auctions.reject');
    Route::delete('auctions/api/multipleDestroy', [AuctionController::class, 'multipleDestroy'])->name('auctions.multipleDestroy');
    Route::get('auctions/title', [AuctionController::class, 'getAuctionByTitle'])->name('auctions.search.title');

    // ------------------ categories
    Route::resource('categories', CategoryController::class)->only(['index', 'update', 'destroy', 'store', 'edit']);
    Route::post('categories/sort', [CategoryController::class, 'sort']);
    Route::post('category/slug', [CategoryController::class, 'generate_slug']);
    Route::get('categories/title', [CategoryController::class, 'getCategoryByTitle'])->name('categories.search.title');

    // ------------------ discounts
    Route::resource('discounts', DiscountController::class)->except(['show']);

    // ------------------ provinces
    Route::resource('provinces', ProvinceController::class);
    Route::post('provinces/api/index', [ProvinceController::class, 'apiIndex'])->name('provinces.apiIndex');
    Route::delete('provinces/api/multipleDestroy', [ProvinceController::class, 'multipleDestroy'])->name('provinces.multipleDestroy');
    Route::post('provinces/api/sort', [ProvinceController::class, 'sort'])->name('provinces.sort');

    // ------------------ cities
    Route::resource('cities', CityController::class)->except(['index']);
    Route::post('cities/api/{province}/index', [CityController::class, 'apiIndex'])->name('cities.apiIndex');
    Route::delete('cities/api/multipleDestroy', [CityController::class, 'multipleDestroy'])->name('cities.multipleDestroy');
    Route::post('cities/api/sort', [CityController::class, 'sort'])->name('cities.sort');

    // ------------------ links
    Route::resource('links', LinkController::class)->except(['show']);
    Route::post('links/sort', [LinkController::class, 'sort']);
    Route::get('links/groups', [LinkController::class, 'groups'])->name('links.groups.index');
    Route::put('links/groups/update', [LinkController::class, 'updateGroups'])->name('links.groups.update');

    // ------------------ pages
    Route::resource('pages', PageController::class)->except(['show']);

    // ------------------ roles
    Route::resource('roles', RoleController::class);

    // ------------------ permissions
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::put('permissions', [PermissionController::class, 'update'])->name('permissions.update');

    // ------------------ redirects
    Route::resource('redirects', RedirectController::class)->only('index', 'store', 'destroy');

    // ------------------ search engine rules
    Route::resource('search-engine-rules', SearchEngineRulesController::class)->only('index', 'store', 'destroy');

    // ------------------ menus
    Route::resource('menus', MenuController::class)->except(['edit']);
    Route::post('menus/sort', [MenuController::class, 'sort']);

    Route::get('notifications', [MainController::class, 'notifications'])->name('notifications');

    // ------------------ comments
    Route::get('comments/{comment}/replies', [CommentController::class, "replies"])->name('comments.replies');
    Route::resource('comments', CommentController::class)->only(['index', 'show', 'destroy', 'update']);

    // ------------------ transactions
    Route::resource('transactions', TransactionController::class)->only(['index', 'show', 'destroy']);
    Route::post('transactions/api/index', [TransactionController::class, 'apiIndex'])->name('transactions.apiIndex');
    Route::delete('transactions/api/multipleDestroy', [TransactionController::class, 'multipleDestroy'])->name('transactions.multipleDestroy');

    // ------------------ widgets
    Route::resource('widgets', WidgetController::class)->except(['show']);
    Route::get('widgets/{key}/template', [WidgetController::class, 'template'])->name('widgets.template');
    Route::post('widget/sort', [WidgetController::class, 'sort'])->name('widgets.sort');

    // ------------------ sliders
    Route::resource('slides', SliderController::class)->except(['show']);
    Route::post('slides/sort', [SliderController::class, 'sort']);

    // ------------------ statistics
    Route::get('statistics/users', [StatisticsController::class, 'users'])->name('statistics.users');
    Route::get('statistics/userCounts', [StatisticsController::class, 'userCounts'])->name('statistics.userCounts');
    Route::get('statistics/orders', [StatisticsController::class, 'orders'])->name('statistics.orders');
    Route::get('statistics/orderUsers', [StatisticsController::class, 'orderUsers'])->name('statistics.orderUsers');
    Route::get('statistics/orderAuctions', [StatisticsController::class, 'orderAuctions'])->name('statistics.orderAuctions');
    Route::get('statistics/orderValues', [StatisticsController::class, 'orderValues'])->name('statistics.orderValues');
    Route::get('statistics/orderCounts', [StatisticsController::class, 'orderCounts'])->name('statistics.orderCounts');

//    // ------------------ refund-requests
//    Route::resource('refund-requests', RefundRequestController::class)->only(['index', 'show', 'destroy']);
//    Route::post('refund-requests/accept/{refund_request}', [RefundRequestController::class, 'accept'])->name('refund-requests.accept');
//    Route::post('refund-requests/reject/{refund_request}', [RefundRequestController::class, 'reject'])->name('refund-requests.reject');
//    Route::post('refund-requests/receive/{refund_request}', [RefundRequestController::class, 'receive'])->name('refund-requests.receive');

    // ------------------ orders
    Route::get('orders/factors', [OrderController::class, 'factors'])->name('orders.factors');
    Route::resource('orders', OrderController::class);
    Route::get('orders/api/userInfo', [OrderController::class, 'userInfo'])->name('orders.userInfo');
    Route::get('orders/api/productsList', [OrderController::class, 'productsList'])->name('orders.productsList');
    Route::post('orders/{order}/shipping-status', [OrderController::class, 'shipping_status'])->name('orders.shipping-status');
    Route::get('orders/{order}/factor', [OrderController::class, 'factor'])->name('orders.factor');
    Route::get('orders/{order}/post-label', [OrderController::class, 'postLabel'])->name('orders.post-label');
    Route::post('orders/api/index', [OrderController::class, 'apiIndex'])->name('orders.apiIndex');
    Route::delete('orders/api/multipleDestroy', [OrderController::class, 'multipleDestroy'])->name('orders.multipleDestroy');
    Route::post('orders/api/multiple-factors', [OrderController::class, 'multipleFactors'])->name('orders.multipleFactors');
    Route::post('orders/api/multiple-shipping-status', [OrderController::class, 'multipleShippingStatus'])->name('orders.multipleShippingStatus');
    Route::get('order/not-completed/products', [OrderController::class, 'notCompleted'])->name('orders.notCompleted');

//    Route::get('statistics/smsLog', [StatisticsController::class, 'smsLog'])->name('statistics.smsLog');
//
//    // ------------------ sms
//    Route::resource('sms', SmsController::class)->only(['show']);

//    // ------------------ settings
//    Route::get('settings/information', [SettingController::class, 'showInformation'])->name('settings.information');
//    Route::post('settings/information', [SettingController::class, 'updateInformation']);
//
//    Route::get('settings/socials', [SettingController::class, 'showSocials'])->name('settings.socials');
//    Route::post('settings/socials', [SettingController::class, 'updateSocials']);
//
//    Route::get('settings/gateways', [SettingController::class, 'showGateways'])->name('settings.gateways');
//    Route::post('settings/gateways', [SettingController::class, 'updateGateways']);
//
//    // Route::get('settings/others', [SettingController::class, 'showOthers'])->name('settings.others');
//    // Route::post('settings/others', [SettingController::class, 'updateOthers']);
//
//    Route::get('settings/sms', [SettingController::class, 'showSms'])->name('settings.sms');
//    Route::post('settings/sms', [SettingController::class, 'updateSms']);

    // ------------------ logs
    Route::get('logs', [LogViewerController::class, 'index'])->name('logs.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
