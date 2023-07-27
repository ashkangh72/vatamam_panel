<?php

return [

    'permissions' => [

        'users' => [
            'title' => 'مدیریت کاربران',
            'values' => [
                'index' => 'لیست کاربران',
                'view' => 'مشاهده کاربر',
                'create' => 'ایجاد کاربر',
                'update' => 'ویرایش کاربر',
                'delete' => 'حذف کاربر',
                'export.excel' => 'خروجی اکسل',
            ]
        ],

        'auctions' => [
            'title' => 'مدیریت مزایده ها',
            'values' => [
                'index' => 'لیست مزایده ها',
                'approve' => 'تایید مزایده',
                'reject' => 'رد مزایده',
            ]
        ],

        'categories' => [
            'title' => 'مدیریت دسته بندی ها',
            'values' => [
                'index' => 'لیست دسته بندی ها',
                'create' => 'ایجاد دسته بندی',
                'update' => 'ویرایش دسته بندی',
                'delete' => 'حذف دسته بندی',
            ]
        ],

        'discounts' => [
            'title' => 'مدیریت تخفیف ها',
            'values' => [
                'index' => 'لیست تخفیف ها',
                'create' => 'ایجاد تخفیف',
                'update' => 'ویرایش تخفیف',
                'delete' => 'حذف تخفیف',
            ]
        ],

        'carriers' => [
            'title' => 'مدیریت حمل و نقل',
            'values' => [
                'provinces.index' => 'لیست استان ها',
                'provinces.update' => 'ویرایش استان',
                'provinces.delete' => 'حذف استان',
                'provinces.create' => 'ایجاد استان',
                'provinces.show' => 'مشاهده استان',
                'cities.update' => 'ویرایش شهر',
                'cities.delete' => 'حذف شهر',
                'cities.create' => 'ایجاد شهر',
            ]
        ],
        'links' => [
            'title' => 'مدیریت لینک های فوتر',
            'values' => [
                'index' => 'لیست لینک ها',
                'create' => 'ایجاد لینک',
                'update' => 'ویرایش لینک',
                'delete' => 'حذف لینک',
                'groups' => 'مدیریت گروه ها',
                'groups.update' => 'ویرایش گروه ها'
            ]
        ],

        'pages' => [
            'title' => 'مدیریت صفحات',
            'values' => [
                'index' => 'لیست صفحات',
                'create' => 'ایجاد صفحه',
                'update' => 'ویرایش صفحه',
                'delete' => 'حذف صفحه',
            ]
        ],

        'roles' => [
            'title' => 'مدیریت مقام ها',
            'values' => [
                'index' => 'لیست مقام ها',
                'create' => 'ایجاد مقام',
                'update' => 'ویرایش مقام',
                'delete' => 'حذف مقام',
            ]
        ],

        'redirects' => [
            'title' => 'مدیریت تغییر مسیرها',
            'values' => [
                'index' => 'لیست تغییر مسیرها',
                'create' => 'ایجاد تغییر مسیرها',
                'delete' => 'حذف تغییر مسیرها',
            ]
        ],

        'search-engine-rules' => [
            'title' => 'robots.txt مدیریت',
            'values' => [
                'index' => 'لیست قوانین robots.txt',
                'create' => 'robots.txt ایجاد قانون',
                'delete' => 'robots.txt حذف قانون',
            ]
        ],

        'menus' => [
            'title' => 'مدیریت منو ها',
            'values' => [
                'index' => 'لیست منو ها',
                'show' => 'نمایش منو',
                'create' => 'ایجاد منو',
                'update' => 'ویرایش منو',
                'delete' => 'حذف منو',
            ]
        ],

        'notifications' => [
            'title' => 'مدیریت اعلان ها',
            'values' => [
                'index' => 'لیست اعلان ها'
            ]
        ],

        'comments' => [
            'title' => 'مدیریت نظرات',
            'values' => [
                'index' => 'لیست نظرات',
                'show' => 'مشاهده نظر',
                'update' => 'ویرایش نظر',
                'delete' => 'حذف نظر',
            ]
        ],

        'transactions' => [
            'title' => 'مدیریت تراکنش ها',
            'values' => [
                'index' => 'لیست تراکنش ها',
                'show' => 'مشاهده تراکنش',
                'delete' => 'حذف تراکنش',
            ]
        ],


//        'attributes' => [
//            'title'  => 'مدیریت ویژگی ها',
//            'values' => [
//                'groups.index'    => 'لیست گروه ویژگی ها',
//                'groups.show'     => 'مشاهده گروه ویژگی',
//                'groups.create'   => 'ایجاد گروه ویژگی',
//                'groups.update'   => 'ویرایش گروه ویژگی',
//                'groups.delete'   => 'حذف گروه ویژگی',
//
//                'index'           => 'لیست ویژگی ها',
//                'create'          => 'ایجاد ویژگی',
//                'update'          => 'ویرایش ویژگی',
//                'delete'          => 'حذف ویژگی',
//            ]
//        ],

//        'orders' => [
//            'title'  => 'مدیریت سفارشات',
//            'values' => [
//                'index'             => 'لیست سفارشات',
//                'create'            => 'افزودن سفارش جدید',
//                'view'              => 'مشاهده سفارش',
//                'update'            => 'ویرایش سفارش',
//                'delete'            => 'حذف سفارش',
//            ]
//        ],


//        'sliders' => [
//            'title'  => 'مدیریت اسلایدرها',
//            'values' => [
//                'index'             => 'لیست اسلایدرها',
//                'create'            => 'ایجاد اسلایدر',
//                'update'            => 'ویرایش اسلایدر',
//                'delete'            => 'حذف اسلایدر',
//            ]
//        ],
//        'statistics' => [
//            'title'  => 'گزارشات',
//            'values' => [
//                'views'         => 'بازدیدها',
//                'viewsCharts'   => 'بازدیدها (نموداری)',
//                'viewers'       => 'بازدیدکنندگان',
//                'eCommerce'     => 'درآمد',
//                'orders'        => 'سفارشات',
//                'users'         => 'کاربران',
//                'sms'           => 'لاگ پیامک های ارسالی',
//            ]
//        ],
//        'contacts' => [
//            'title'  => 'مدیریت تماس با ما',
//            'values' => [
//                'index'             => 'لیست تماس با ما',
//                'view'              => 'مشاهده تماس با ما',
//                'delete'            => 'حذف تماس با ما',
//            ]
//        ],

//        'settings' => [
//            'title'  => 'تنظیمات',
//            'values' => [
//                'information'        => 'اطلاعات سایت',
//                'socials'            => 'شبکه های اجتماعی',
//                'gateway'            => 'درگاه های پرداخت',
//                'others'             => 'تنظیمات دیگر',
//                'sms'                => 'تنظیمات پیامک',
//                'ftp'                => 'تنظیمات ftp',
//            ]
//        ],


    ],

    'linkGroups' => [
        [
            'name' => 'گروه اول',
            'key' => 1,
        ],
        [
            'name' => 'گروه دوم',
            'key' => 2,
        ],
        [
            'name' => 'گروه سوم',
            'key' => 3,
        ],
    ],
];
