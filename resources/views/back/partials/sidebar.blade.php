<div class="main-menu menu-fixed menu-accordion menu-shadow {{ user_option('theme_color') == 'dark' ? 'menu-dark' : 'menu-light' }}" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{ url('/') }}" target="_blank">
                    <h2 class="brand-text mb-0">{{ env('APP_NAME') }}</h2>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="{{ active_class('admin.dashboard') }} nav-item">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="feather icon-home"></i>
                    <span class="menu-title">داشبورد</span>
                </a>
            </li>

            @can('users')
                <li class="nav-item has-sub {{ open_class(['admin.users.*']) }}">
                    <a href="#">
                        <i class="feather icon-users"></i>
                        <span class="menu-title" > کاربران</span>
                    </a>
                    <ul class="menu-content">
                        @can('users.index')
                            <li class="{{ active_class('admin.users.index') }}">
                                <a href="{{ route('admin.users.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">لیست کاربران</span>
                                </a>
                            </li>
                        @endcan

                        @can('users.create')
                            <li class="{{ active_class('admin.users.create') }}">
                                <a href="{{ route('admin.users.create') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">ایجاد کاربر</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('auctions')
                <li class="nav-item has-sub {{ open_class(['admin.auctions.*']) }}">
                    <a href="#">
                        <i class="feather icon-shopping-cart"></i>
                        <span class="menu-title" > مزایده ها</span>
                    </a>
                    <ul class="menu-content">
                        @can('auctions.index')
                            <li class="{{ active_class('admin.auctions.index') }}">
                                <a href="{{ route('admin.auctions.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">لیست مزایده ها</span>
                                </a>
                            </li>
                        @endcan
                        @can('categories.index')
                            <li class="{{ active_class('admin.categories.*') }}">
                                <a href="{{ route('admin.categories.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">دسته بندی ها</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('discounts')
                <li class="nav-item has-sub {{ open_class(['admin.discounts.*']) }}">
                    <a href="#">
                        <i class="feather icon-tag"></i>
                        <span class="menu-title"> تخفیف ها</span>
                    </a>
                    <ul class="menu-content">
                        @can('discounts.index')
                            <li class="{{ active_class('admin.discounts.index') }}">
                                <a class="{{ active_class('admin.discounts.index') }}" href="{{ route('admin.discounts.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">لیست تخفیف ها</span>
                                </a>
                            </li>
                        @endcan
                        @can('discounts.create')
                            <li class="{{ active_class('admin.discounts.create') }}">
                                <a class="{{ active_class('admin.discounts.create') }}" href="{{ route('admin.discounts.create') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">ایجاد تخفیف</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('carriers')
                <li class="nav-item has-sub {{ open_class(['admin.provinces.*', 'admin.cities.*']) }}">
                    <a href="#">
                        <i class="feather icon-package"></i>
                        <span class="menu-title"> حمل و نقل</span>
                    </a>
                    <ul class="menu-content">
                        @can('carriers.provinces.index')
                            <li class="{{ active_class('admin.provinces.*') }}">
                                <a class="{{ active_class('admin.provinces.index') }}" href="{{ route('admin.provinces.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">لیست استان ها</span>
                                </a>
                            </li>
                        @endcan
                        @can('carriers.provinces.create')
                            <li class="{{ active_class('admin.provinces.*') }}">
                                <a class="{{ active_class('admin.provinces.create') }}" href="{{ route('admin.provinces.create') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">ایجاد استان</span>
                                </a>
                            </li>
                        @endcan
                        @can('carriers.cities.create')
                            <li class="{{ active_class('admin.cities.*') }}">
                                <a class="{{ active_class('admin.cities.create') }}" href="{{ route('admin.cities.create') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">ایجاد شهر</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('links')
                <li class="nav-item has-sub {{ open_class(['admin.links.*']) }}">
                    <a href="#">
                        <i class="feather icon-link"></i>
                        <span class="menu-title" > لینک های فوتر</span>
                    </a>
                    <ul class="menu-content">
                        @can('links.index')
                            <li class="{{ active_class('admin.links.index') }}">
                                <a href="{{ route('admin.links.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">لیست لینک ها</span>
                                </a>
                            </li>
                        @endcan
                        @can('links.create')
                            <li class="{{ active_class('admin.links.create') }}">
                                <a href="{{ route('admin.links.create') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">ایجاد لینک </span>
                                </a>
                            </li>
                        @endcan
                        @can('links.groups')
                            <li class="{{ active_class('admin.links.groups.index') }}">
                                <a href="{{ route('admin.links.groups.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">لیست گروه ها</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('pages')
                <li class="nav-item has-sub {{ open_class(['admin.pages.*']) }}">
                    <a href="#">
                        <i class="feather icon-file"></i>
                        <span class="menu-title" >صفحات</span>
                    </a>
                    <ul class="menu-content">
                        @can('pages.index')
                            <li class="{{ active_class('admin.pages.index') }}">
                                <a href="{{ route('admin.pages.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">لیست صفحات</span>
                                </a>
                            </li>
                        @endcan
                        @can('pages.create')
                            <li class="{{ active_class('admin.pages.create') }}">
                                <a href="{{ route('admin.pages.create') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">ایجاد صفحه</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('roles')
                <li class="nav-item has-sub {{ open_class(['admin.roles.*']) }}">
                    <a href="#">
                        <i class="feather icon-unlock"></i>
                        <span class="menu-title" > نقش ها</span>
                    </a>
                    <ul class="menu-content">
                        @can('roles.index')
                            <li class="{{ active_class('admin.roles.index') }}">
                                <a href="{{ route('admin.roles.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">لیست نقش ها</span>
                                </a>
                            </li>
                        @endcan
                        @can('roles.create')
                            <li class="{{ active_class('admin.roles.create') }}">
                                <a href="{{ route('admin.roles.create') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">ایجاد نقش</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('widgets')
                <li class="nav-item has-sub {{ open_class(['admin.widgets.*', 'admin.slides.*']) }}">
                    <a href="#">
                        <i class="feather icon-layout"></i>
                        <span class="menu-title" >مدیریت صفحه اصلی</span>
                    </a>
                    <ul class="menu-content">
                        @can('widgets')
                            @if (config('general.widgets'))
                                <li class="{{ active_class('admin.widgets.index') }}">
                                    <a href="{{ route('admin.widgets.index') }}">
                                        <i class="feather icon-circle"></i>
                                        <span class="menu-item">ابزارک ها</span>
                                    </a>
                                </li>
                            @endif
                        @endcan
                        @can('slides')
                            <li class="{{ open_class(['admin.slides.*']) }}">
                                <a href="#">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-title" >اسلایدرها</span>
                                </a>
                                <ul class="menu-content">
                                    @can('slides.index')
                                        <li class="{{ active_class('admin.slides.index') }}">
                                            <a href="{{ route('admin.slides.index') }}">
                                                <i class="feather icon-circle"></i>
                                                <span class="menu-item">لیست اسلایدرها</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('slides.create')
                                        <li class="{{ active_class('admin.slides.create') }}">
                                            <a href="{{ route('admin.slides.create') }}">
                                                <i class="feather icon-circle"></i>
                                                <span class="menu-item">ایجاد اسلایدر</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('statistics')
            <li class="nav-item has-sub {{ open_class(['admin.statistics.*']) }}">
                <a href="#"><i class="feather icon-pie-chart"></i><span class="menu-title" >گزارشات</span></a>
                <ul class="menu-content">
                    @can('statistics.orders')
                        <li class="{{ active_class('admin.statistics.orders') }}">
                            <a href="{{ route('admin.statistics.orders') }}"><i class="feather icon-circle"></i><span class="menu-item">سفارشات</span></a>
                        </li>
                    @endcan
                    @can('statistics.users')
                        <li class="{{ active_class('admin.statistics.users') }}">
                            <a href="{{ route('admin.statistics.users') }}"><i class="feather icon-circle"></i><span class="menu-item">کاربران</span></a>
                        </li>
                    @endcan
                    @can('statistics.sms')
                        <li class="{{ active_class('admin.statistics.smsLog') }}">
                            <a href="{{ route('admin.statistics.smsLog') }}"><i class="feather icon-circle"></i><span class="menu-item"> لاگ پیامک های ارسالی</span></a>
                        </li>
                    @endcan
                </ul>
            </li>
            @endcan
            @can('orders')
                <li class="nav-item has-sub {{ open_class(['admin.orders.*']) }}"><a href="#"><i class="feather icon-briefcase"></i><span class="menu-title" > سفارشات</span></a>
                    <ul class="menu-content">
                        @can('orders.index')
                            <li class="{{ active_class('admin.orders.index') }}">
                                <a href="{{ route('admin.orders.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">همه سفارشات</span>
                                    <span class="badge badge-transparent ml-1">{{ $ordersCount }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('redirects')
                <li class="{{ active_class('admin.redirects.*') }} nav-item">
                    <a href="{{ route('admin.redirects.index') }}">
                        <i class="feather icon-external-link"></i>
                        <span class="menu-title">تغییر مسیرها</span>
                    </a>
                </li>
            @endcan
            @can('search-engine-rules')
                <li class="{{ active_class('admin.search-engine-rules.*') }} nav-item">
                    <a href="{{ route('admin.search-engine-rules.index') }}">
                        <i class="feather icon-globe"></i>
                        <span class="menu-title">robots.txt</span>
                    </a>
                </li>
            @endcan
            @can('menus')
                <li class="{{ active_class('admin.menus.*') }} nav-item">
                    <a href="{{ route('admin.menus.index') }}">
                        <i class="feather icon-menu"></i>
                        <span class="menu-title">منوها</span>
                    </a>
                </li>
            @endcan
            @can('notifications')
                <li class="{{ active_class('admin.notifications.*') }} nav-item">
                    <a href="{{ route('admin.notifications') }}">
                        <i class="feather icon-bell"></i>
                        <span class="menu-title">اعلان ها</span>
                        @if($notificationsCount)
                            <span class="badge badge badge-primary badge-pill float-right mr-2"> {{ $notificationsCount }}</span>
                        @endif
                    </a>
                </li>
            @endcan
            @can('comments')
                <li class="{{ active_class('admin.comments.*') }} nav-item"><a href="{{ route('admin.comments.index') }}">
                        <i class="feather icon-message-circle"></i>
                        <span class="menu-title"> نظرات</span>
                        @if($commentsCount)
                            <span class="badge badge badge-primary badge-pill float-right mr-2"> {{ $commentsCount }}</span>
                        @endif
                    </a>
                </li>
            @endcan
            @can('transactions')
                <li class="{{ active_class('admin.transactions.*') }} nav-item">
                    <a href="{{ route('admin.transactions.index') }}">
                        <i class="feather icon-credit-card"></i>
                        <span class="menu-title"> لیست تراکنش ها</span>
                    </a>
                </li>
            @endcan

            @can('settings')
                <li class="nav-item has-sub {{ open_class(['admin.settings.*']) }}">
                    <a href="#"><i class="feather icon-settings"></i><span class="menu-title" >تنظیمات</span></a>
                    <ul class="menu-content">
                        @can('settings.information')
                            <li class="{{ active_class('admin.settings.information') }}">
                                <a href="{{ route('admin.settings.information') }}"><i class="feather icon-circle"></i><span class="menu-item">اطلاعات کلی</span></a>
                            </li>
                        @endcan

                        @can('settings.socials')
                            @if (config('general.socials'))
                                <li class="{{ active_class('admin.settings.socials') }}">
                                    <a href="{{ route('admin.settings.socials') }}"><i class="feather icon-circle"></i><span class="menu-item">شبکه های اجتماعی</span></a>
                                </li>
                            @endif
                        @endcan

{{--                        @can('settings.sms')--}}
{{--                            <li class="{{ active_class('admin.settings.sms') }}">--}}
{{--                                <a href="{{ route('admin.settings.sms') }}"><i class="feather icon-circle"></i><span class="menu-item">تنظیمات پیامک</span></a>--}}
{{--                            </li>--}}
{{--                        @endcan--}}
                    </ul>
                </li>
            @endcan

            @if(auth()->user()->level == 'creator')
                <li class="nav-item has-sub {{ open_class(['admin.permissions.*', 'admin.developer.*', 'admin.logs.*']) }}">
                    <a href="#"><i class="feather icon-alert-octagon"></i><span class="menu-title" >توسعه دهنده</span><span class="badge badge-danger badge-pill float-right mr-2">creator</span></a>
                    <ul class="menu-content">
                        <li class="{{ active_class('admin.permissions.*') }}">
                            <a href="{{ route('admin.permissions.index') }}"><i class="feather icon-circle"></i><span class="menu-item">دسترسی ها</span></a>
                        </li>

                        <li class="{{ active_class('admin.logs.*') }}">
                            <a target="_blank" href="{{ route('admin.logs.index') }}"><i class="feather icon-circle"></i><span class="menu-item">لاگ ها</span></a>
                        </li>
                    </ul>
                </li>
            @endif

        </ul>
    </div>
</div>
