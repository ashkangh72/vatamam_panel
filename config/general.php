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

        'widgets' => [
            'title' => 'مدیریت ابزارک ها',
            'values' => [
                'index' => 'لیست ابزارک ها',
                'show' => 'مشاهده ابزارک',
                'create' => 'ایجاد ابزارک',
                'update' => 'ویرایش ابزارک',
                'delete' => 'حذف ابزارک',
            ]
        ],

        'slides' => [
            'title' => 'مدیریت اسلایدرها',
            'values' => [
                'index' => 'لیست اسلایدرها',
                'create' => 'ایجاد اسلاید',
                'update' => 'ویرایش اسلاید',
                'delete' => 'حذف اسلاید',
            ]
        ],

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
//        'settings' => [
//            'title'  => 'تنظیمات',
//            'values' => [
//                'information'        => 'اطلاعات سایت',
//                'socials'            => 'شبکه های اجتماعی',
//                'gateway'            => 'درگاه های پرداخت',
//                'others'             => 'تنظیمات دیگر',
//                'sms'                => 'تنظیمات پیامک',
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

    'widgets' => [
        'mobile_slider' => [
            'title' => 'اسلایدر موبایل',
            'options' => [
                [
                    'title' => 'تعداد قابل نمایش',
                    'key' => 'number',
                    'input-type' => 'input',
                    'type' => 'number',
                    'default' => '5',
                    'class' => 'col-md-4 col-6',
                    'attributes' => 'required'
                ]
            ],
            'rules' => [
                'number' => 'required',
            ]
        ],
        'desktop_slider' => [
            'title' => 'اسلایدر دسکتاپ',
            'options' => [
                [
                    'title' => 'تعداد قابل نمایش',
                    'key' => 'number',
                    'input-type' => 'input',
                    'type' => 'number',
                    'default' => '5',
                    'class' => 'col-md-4 col-6',
                    'attributes' => 'required'
                ]
            ],
            'rules' => [
                'number' => 'required',
            ]
        ],
        'service_slider' => [
            'title' => 'اسلایدر خدمات',
            'options' => [
                [
                    'title' => 'تعداد قابل نمایش',
                    'key' => 'number',
                    'input-type' => 'input',
                    'type' => 'number',
                    'default' => '5',
                    'class' => 'col-md-4 col-6',
                    'attributes' => 'required'
                ]
            ],
            'rules' => [
                'number' => 'required',
            ]
        ],
        'customer_slider' => [
            'title' => 'اسلایدر مشتریان',
            'options' => [
                [
                    'title' => 'تعداد قابل نمایش',
                    'key' => 'number',
                    'input-type' => 'input',
                    'type' => 'number',
                    'default' => '5',
                    'class' => 'col-md-4 col-6',
                    'attributes' => 'required'
                ]
            ],
            'rules' => [
                'number' => 'required',
            ]
        ],
        'statistic_slider' => [
            'title' => 'اسلایدر آمار',
            'options' => [
                [
                    'title' => 'تعداد قابل نمایش',
                    'key' => 'number',
                    'input-type' => 'input',
                    'type' => 'number',
                    'default' => '3',
                    'class' => 'col-md-4 col-6',
                    'attributes' => 'required'
                ]
            ],
            'rules' => [
                'number' => 'required',
            ]
        ],
    ],

    'sliderGroups' => [
        [
            'group' => 'desktop_slider',
            'name'  => 'اسلایدر دسکتاپ',
            'width' => 1780,
            'height' => 890,
            'count' => 2,
            'size'  => '890 * 1780'
        ],
        [
            'group' => 'mobile_slider',
            'name'  => 'اسلایدر حالت موبایل',
            'width' => 300,
            'height' => 250,
            'count' => 5,
            'size'  => '250 * 300'
        ],
        [
            'group' => 'service_slider',
            'name' => 'اسلایدر خدمات',
            'width' => 100,
            'height' => 85,
            'count' => 3,
            'size' => '100 * 85'
        ],
        [
            'group' => 'customer_slider',
            'name' => 'اسلایدر مشتریان',
            'width' => 100,
            'height' => 100,
            'count' => 3,
            'size' => '100 * 100'
        ],
        [
            'group' => 'statistic_slider',
            'name' => 'اسلایدر آمار',
            'width' => 100,
            'height' => 100,
            'count' => 3,
            'size' => '100 * 100'
        ],
    ],
];
