<?php

return [

    'api_url' => 'https://api.shaniland.com/v1',

    'admin_route_prefix' => env('ADMIN_ROUTE_PREFIX'),

    'current_theme' => env('CURRENT_THEME', 'DefaultTheme'),

    'permissions' => [

        'users' => [
            'title'  => 'مدیریت کاربران',
            'values' => [
                'index'          => 'لیست کاربران',
                'view'           => 'مشاهده کاربر',
                'create'         => 'ایجاد کاربر',
                'update'         => 'ویرایش کاربر',
                'delete'         => 'حذف کاربر',
                'export.excel'   => 'خروجی اکسل',
                'marketing-requests'        => 'مدیریت لیست درخواست های بازاریابی',
                'marketing-requests.accept' => 'تایید درخواست بازاریابی',
                'marketing-requests.reject' => 'رد درخواست بازاریابی',
                'marketing-campaigns'         => 'مدیریت کمپین های بازاریابی',
                'marketing-campaigns.create'  => 'ایجاد کمپین بازاریابی',
                'marketing-campaigns.update'  => 'ویرایش کمپین بازاریابی',
                'marketing-campaigns.tariffs' => 'نمایش تعرفه های کمپین بازاریابی',
                'marketing-commission-deposit-requests'        => 'مدیریت لیست درخواست های برداشت کمیسیون',
                'marketing-commission-deposit-requests.accept' => 'تایید درخواست برداشت کمیسیون',
                'marketing-commission-deposit-requests.reject' => 'رد درخواست برداشت کمیسیون',
            ]
        ],
        'auctions'=>[
            'title' => "مدیریت مزایده ها",
            'values'=>[
                'index' => 'لیست مزایده ها'
            ]
        ],

        'posts' => [
            'title'  => 'مدیریت نوشته ها',
            'values' => [
                'index'      => 'لیست نوشته ها',
                'create'     => 'ایجاد نوشته',
                'update'     => 'ویرایش نوشته',
                'delete'     => 'حذف نوشته',
                'category'   => 'مدیریت دسته بندی ها',
            ]
        ],

        'products' => [
            'title'  => 'مدیریت محصولات',
            'values' => [
                'index'         => 'لیست محصولات',
                'create'        => 'ایجاد محصول',
                'update'        => 'ویرایش محصول',
                'delete'        => 'حذف محصول',
                'export'        => 'خروجی گرفتن',
                'category'      => 'مدیریت دسته بندی ها',
                'spectypes'     => 'مدیریت نوع مشخصات',
                'size-types'    => 'مدیریت سایزبندی ها',
                'stock-notify'  => 'مدیریت لیست اطلاع از موجودی',
                'refund-requests'          => 'مدیریت لیست درخواست های بازگردانی',
                'refund-requests.show'     => 'مشاهده درخواست بازگردانی',
                'refund-requests.delete'   => 'حذف درخواست بازگردانی',
                'refund-requests.accept'   => 'تایید درخواست بازگردانی',
                'refund-requests.reject'   => 'رد درخواست بازگردانی',
                'refund-requests.receive'  => 'دریافت محصول بازگردانی شده',
                'brands'        => 'مدیریت برندها',
                'prices'        => 'قیمت ها',
            ]
        ],

        'discounts' => [
            'title'  => 'مدیریت تخفیف ها',
            'values' => [
                'index'             => 'لیست تخفیف ها',
                'create'            => 'ایجاد تخفیف',
                'update'            => 'ویرایش تخفیف',
                'delete'            => 'حذف تخفیف',
            ]
        ],

        'attributes' => [
            'title'  => 'مدیریت ویژگی ها',
            'values' => [
                'groups.index'    => 'لیست گروه ویژگی ها',
                'groups.show'     => 'مشاهده گروه ویژگی',
                'groups.create'   => 'ایجاد گروه ویژگی',
                'groups.update'   => 'ویرایش گروه ویژگی',
                'groups.delete'   => 'حذف گروه ویژگی',

                'index'           => 'لیست ویژگی ها',
                'create'          => 'ایجاد ویژگی',
                'update'          => 'ویرایش ویژگی',
                'delete'          => 'حذف ویژگی',
            ]
        ],

        'filters' => [
            'title'  => 'مدیریت فیلترها',
            'values' => [
                'index'    => 'لیست فیلترها',
                'create'   => 'ایجاد فیلتر',
                'update'   => 'ویرایش فیلتر',
                'delete'   => 'حذف فیلتر',
            ]
        ],

        'orders' => [
            'title'  => 'مدیریت سفارشات',
            'values' => [
                'index'             => 'لیست سفارشات',
                'create'            => 'افزودن سفارش جدید',
                'view'              => 'مشاهده سفارش',
                'update'            => 'ویرایش سفارش',
                'delete'            => 'حذف سفارش',
            ]
        ],

        'carriers' => [
            'title'  => 'مدیریت حمل و نقل',
            'values' => [
                'provinces.index'             => 'لیست استان ها',
                'provinces.update'            => 'ویرایش استان',
                'provinces.delete'            => 'حذف استان',
                'provinces.create'            => 'ایجاد استان',
                'provinces.show'              => 'مشاهده استان',
                'cities.update'               => 'ویرایش شهر',
                'cities.delete'               => 'حذف شهر',
                'cities.create'               => 'ایجاد شهر',
                'shipping-cost'               => 'مدیریت هزینه های ارسال',
            ]
        ],

        'faqs' => [
            'title'  => 'مدیریت سوالات متداول',
            'values' => [
                'index'             => 'لیست سوالات متداول',
                'update'            => 'ویرایش سوال متداول',
                'delete'            => 'حذف سوال متداول',
                'create'            => 'ایجاد سوال متداول',
            ]
        ],

        'sliders' => [
            'title'  => 'مدیریت اسلایدرها',
            'values' => [
                'index'             => 'لیست اسلایدرها',
                'create'            => 'ایجاد اسلایدر',
                'update'            => 'ویرایش اسلایدر',
                'delete'            => 'حذف اسلایدر',
            ]
        ],

        'banners' => [
            'title'  => 'مدیریت بنرها',
            'values' => [
                'index'             => 'لیست بنرها',
                'create'            => 'ایجاد بنر',
                'update'            => 'ویرایش بنر',
                'delete'            => 'حذف بنر',
            ]
        ],

        'links' => [
            'title'  => 'مدیریت لینک های فوتر',
            'values' => [
                'index'             => 'لیست لینک ها',
                'create'            => 'ایجاد لینک',
                'update'            => 'ویرایش لینک',
                'delete'            => 'حذف لینک',
                'groups'            => 'مدیریت گروه ها'
            ]
        ],

        'backups' => [
            'title'  => 'مدیریت بکاپ ها',
            'values' => [
                'index'             => 'لیست بکاپ ها',
                'create'            => 'ایجاد بکاپ',
                'download'          => 'دانلود بکاپ',
                'delete'            => 'حذف بکاپ',
            ]
        ],

        'pages' => [
            'title'  => 'مدیریت صفحات',
            'values' => [
                'index'             => 'لیست صفحات',
                'create'            => 'ایجاد صفحه',
                'update'            => 'ویرایش صفحه',
                'delete'            => 'حذف صفحه',
            ]
        ],

        'roles' => [
            'title'  => 'مدیریت مقام ها',
            'values' => [
                'index'             => 'لیست مقام ها',
                'create'            => 'ایجاد مقام',
                'update'            => 'ویرایش مقام',
                'delete'            => 'حذف مقام',
            ]
        ],

        'statistics' => [
            'title'  => 'گزارشات',
            'values' => [
                'views'         => 'بازدیدها',
                'viewsCharts'   => 'بازدیدها (نموداری)',
                'viewers'       => 'بازدیدکنندگان',
                'eCommerce'     => 'درآمد',
                'orders'        => 'سفارشات',
                'users'         => 'کاربران',
                'sms'           => 'لاگ پیامک های ارسالی',
            ]
        ],

        'themes' => [
            'title'  => 'مدیریت قالب ها',
            'values' => [
                'index'             => 'لیست قالب ها',
                'create'            => 'افزودن قالب',
                'update'            => 'تغییر قالب',
                'delete'            => 'حذف قالب',
                'settings'          => 'تنظیمات قالب',
                'widgets'           => 'مدیریت صفحه اصلی'
            ]
        ],

        'file-manager'    => 'مدیریت فایل ها',

        'tickets' => [
            'title'  => 'مدیریت تیکت ها',
            'values' => [
                'index'             => 'لیست تیکت ها',
                'show'              => 'مشاهده تیکت',
                'create'            => 'ایجاد تیکت',
                'update'            => 'ویرایش تیکت',
                'delete'            => 'حذف تیکت',
            ]
        ],

        'menus' => [
            'title'  => 'مدیریت منو ها',
            'values' => [
                'index'             => 'لیست منو ها',
                'create'            => 'ایجاد منو',
                'update'            => 'ویرایش منو',
                'delete'            => 'حذف منو',
            ]
        ],

        'transactions' => [
            'title'  => 'مدیریت تراکنش ها',
            'values' => [
                'index'             => 'لیست تراکنش ها',
                'view'              => 'مشاهده تراکنش',
                'delete'            => 'حذف تراکنش',
            ]
        ],

        'contacts' => [
            'title'  => 'مدیریت تماس با ما',
            'values' => [
                'index'             => 'لیست تماس با ما',
                'view'              => 'مشاهده تماس با ما',
                'delete'            => 'حذف تماس با ما',
            ]
        ],

        'comments' => [
            'title'  => 'مدیریت نظرات',
            'values' => [
                'index'             => 'لیست نظرات',
                'view'              => 'مشاهده نظر',
                'update'             => 'ویرایش نظر',
                'delete'            => 'حذف نظر',
            ]
        ],

        'settings' => [
            'title'  => 'تنظیمات',
            'values' => [
                'information'        => 'اطلاعات سایت',
                'socials'            => 'شبکه های اجتماعی',
                'gateway'            => 'درگاه های پرداخت',
                'others'             => 'تنظیمات دیگر',
                'sms'                => 'تنظیمات پیامک',
                'ftp'                => 'تنظیمات ftp',
            ]
        ],

        'search-engine-rules' => [
            'title'  => 'robots.txt مدیریت',
            'values' => [
                'create'    => 'robots.txt ایجاد قانون',
                'delete'    => 'robots.txt حذف قانون',
            ]
        ],

        'redirects' => [
            'title'  => 'مدیریت تغییر مسیرها',
            'values' => [
                'create'    => 'ایجاد تغییر مسیرها',
                'delete'    => 'حذف تغییر مسیرها',
            ]
        ],

    ],

    'static_menus' => [
        'posts' => [
            'title' => 'وبلاگ'
        ],
        'products' => [
            'title' => 'محصولات',
        ]
    ],

    'supported_gateways' => [
        'behpardakht' => 'به پرداخت ملت',
        'payir'       => 'pay.ir',
        'zarinpal'    => 'زرین پال',
        'payping'     => 'پی پینگ',
        'saman'       => 'سامان',
        'sepehr'      => 'بانک صادرات',
        'idpay'       => 'آیدی پی',
        'rayanpay'    => 'رایان پی',
    ],

    'ftp' => [
        'active' => env('FTP_ACTIVE', 0),
        'ssl' => env('FTP_SSL', 0),
        'host' => env('FTP_HOST'),
        'port' => env('FTP_PORT', 21),
        'root' => env('FTP_ROOT', ''),
        'username' => env('FTP_USERNAME'),
        'password' => env('FTP_PASSWORD'),
    ],


];
