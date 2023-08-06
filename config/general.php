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
        'auction' => [
            'title' => 'ردیف مزایده',
            'options' => [
                [
                    'title'      => 'نوع مرتب سازی',
                    'key'        => 'sort_type',
                    'input-type' => 'select',
                    'class'      => 'col-md-4 col-6',
                    'options'    => [
                        [
                            'value' => 'latest',
                            'title' => 'جدیدترین'
                        ],
                        [
                            'value' => 'last_end_at',
                            'title' => 'بیشترین زمان باقی مانده'
                        ],
                        [
                            'value' => 'end_at',
                            'title' => 'کمترین زمان باقی مانده'
                        ],
                        [
                            'value' => 'expensive',
                            'title' => 'گرانترین'
                        ],
                        [
                            'value' => 'cheap',
                            'title' => 'ارزانترین'
                        ],
                    ],
                    'attributes' => 'required'
                ],
                [
                    'title'      => 'تعداد',
                    'key'        => 'number',
                    'input-type' => 'input',
                    'type'       => 'number',
                    'class'      => 'col-md-4 col-6',
                    'default'    => '10',
                    'attributes' => 'required'
                ],
                [
                    'title'      => 'انتخاب دسته بندی ها (اختیاری)',
                    'key'        => 'categories',
                    'input-type' => 'categories',
                    'class'      => 'col-md-9',
                ],
                [
                    'title'      => 'شامل محصولات زیر دسته ها',
                    'key'        => 'sub_category_auction',
                    'input-type' => 'select',
                    'class'      => 'col-md-3',
                    'options'    => [
                        [
                            'value' => 'yes',
                            'title' => 'بله'
                        ],
                        [
                            'value' => 'no',
                            'title' => 'خیر'
                        ]
                    ],
                ],
                [
                    'title'      => 'انتخاب دوره زمانی (اختیاری)',
                    'key'        => 'historical_period',
                    'input-type' => 'historical_period',
                    'class'      => 'col-md-6',
                ],
                [
                    'title'      => 'انتخاب اصالت (اختیاری)',
                    'key'        => 'originality',
                    'input-type' => 'originality',
                    'class'      => 'col-md-6',
                ],
                [
                    'title'      => 'انتخاب وضعیت کالا (اختیاری)',
                    'key'        => 'condition',
                    'input-type' => 'condition',
                    'class'      => 'col-md-6',
                ],
                [
                    'title'      => 'انتخاب منطقه زمانی (اختیاری)',
                    'key'        => 'timezone',
                    'input-type' => 'timezone',
                    'class'      => 'col-md-6',
                ],
                [
                    'title'      => 'نمایش فقط مزایدات تضمینی (اختیاری)',
                    'key'        => 'show_only_guaranteed',
                    'input-type' => 'select',
                    'class'      => 'col-md-3',
                    'options'    => [
                        [
                            'value' => '',
                            'title' => 'انتخاب کنید...'
                        ],
                        [
                            'value' => 'yes',
                            'title' => 'بله'
                        ],
                        [
                            'value' => 'no',
                            'title' => 'خیر'
                        ]
                    ],
                ],
                [
                    'title'      => 'لینک',
                    'key'        => 'link',
                    'input-type' => 'input',
                    'type'       => 'text',
                    'class'      => 'col-6',
                ],
                [
                    'title'      => 'عنوان لینک',
                    'key'        => 'link_title',
                    'input-type' => 'input',
                    'type'       => 'text',
                    'class'      => 'col-md-3 col-6',
                ],
            ],
            'rules' => [
                'sort_type'        => 'required|in:latest,sell',
                'link'             => 'nullable|string',
                'link_title'       => 'nullable|string',
                'number'           => 'required',
                'categories'       => 'nullable|array',
                'categories.*'     => 'nullable|exists:categories,id',
                'originality'      => 'nullable|exists:originality,id',
                'historical_period'     => 'nullable|exists:historical_periods,id',
            ]
        ],
        'amazing_offer' => [
            'title' => 'ردیف پیشنهاد شگفت انگیز',
            'options' => [
                [
                    'title'      => 'نوع مرتب سازی',
                    'key'        => 'sort_type',
                    'input-type' => 'select',
                    'class'      => 'col-md-4 col-6',
                    'options'    => [
                        [
                            'value' => 'latest',
                            'title' => 'جدیدترین'
                        ],
                        [
                            'value' => 'last_end_at',
                            'title' => 'بیشترین زمان باقی مانده'
                        ],
                        [
                            'value' => 'end_at',
                            'title' => 'کمترین زمان باقی مانده'
                        ],
                        [
                            'value' => 'expensive',
                            'title' => 'گرانترین'
                        ],
                        [
                            'value' => 'cheap',
                            'title' => 'ارزانترین'
                        ],
                    ],
                    'attributes' => 'required'
                ],
                [
                    'title'      => 'تعداد',
                    'key'        => 'number',
                    'input-type' => 'input',
                    'type'       => 'number',
                    'class'      => 'col-md-4 col-6',
                    'default'    => '10',
                    'attributes' => 'required'
                ],
                [
                    'title'      => 'انتخاب دسته بندی ها (اختیاری)',
                    'key'        => 'categories',
                    'input-type' => 'categories',
                    'class'      => 'col-md-9',
                ],
                [
                    'title'      => 'شامل محصولات زیر دسته ها',
                    'key'        => 'sub_category_auction',
                    'input-type' => 'select',
                    'class'      => 'col-md-3',
                    'options'    => [
                        [
                            'value' => 'yes',
                            'title' => 'بله'
                        ],
                        [
                            'value' => 'no',
                            'title' => 'خیر'
                        ]
                    ],
                ],
                [
                    'title'      => 'انتخاب دوره زمانی (اختیاری)',
                    'key'        => 'historical_period',
                    'input-type' => 'historical_period',
                    'class'      => 'col-md-6',
                ],
                [
                    'title'      => 'انتخاب اصالت (اختیاری)',
                    'key'        => 'originality',
                    'input-type' => 'originality',
                    'class'      => 'col-md-6',
                ],
                [
                    'title'      => 'انتخاب وضعیت کالا (اختیاری)',
                    'key'        => 'condition',
                    'input-type' => 'condition',
                    'class'      => 'col-md-6',
                ],
                [
                    'title'      => 'انتخاب منطقه زمانی (اختیاری)',
                    'key'        => 'timezone',
                    'input-type' => 'timezone',
                    'class'      => 'col-md-6',
                ],
                [
                    'title'      => 'نمایش فقط مزایدات تضمینی (اختیاری)',
                    'key'        => 'show_only_guaranteed',
                    'input-type' => 'select',
                    'class'      => 'col-md-3',
                    'options'    => [
                        [
                            'value' => '',
                            'title' => 'انتخاب کنید...'
                        ],
                        [
                            'value' => 'yes',
                            'title' => 'بله'
                        ],
                        [
                            'value' => 'no',
                            'title' => 'خیر'
                        ]
                    ],
                ],
                [
                    'title'      => 'لینک',
                    'key'        => 'link',
                    'input-type' => 'input',
                    'type'       => 'text',
                    'class'      => 'col-6',
                ],
                [
                    'title'      => 'عنوان لینک',
                    'key'        => 'link_title',
                    'input-type' => 'input',
                    'type'       => 'text',
                    'class'      => 'col-md-3 col-6',
                ],
            ],
            'rules' => [
                'sort_type'        => 'required|in:latest,sell',
                'link'             => 'nullable|string',
                'link_title'       => 'nullable|string',
                'number'           => 'required',
                'categories'       => 'nullable|array',
                'categories.*'     => 'nullable|exists:categories,id',
                'originality'      => 'nullable|exists:originality,id',
                'historical_period'     => 'nullable|exists:historical_periods,id',
            ]
        ],
        'instant_offer' => [
            'title' => 'ردیف پیشنهاد لحظه ای',
            'options' => [
                [
                    'title'      => 'نوع مرتب سازی',
                    'key'        => 'sort_type',
                    'input-type' => 'select',
                    'class'      => 'col-md-4 col-6',
                    'options'    => [
                        [
                            'value' => 'latest',
                            'title' => 'جدیدترین'
                        ],
                        [
                            'value' => 'last_end_at',
                            'title' => 'بیشترین زمان باقی مانده'
                        ],
                        [
                            'value' => 'end_at',
                            'title' => 'کمترین زمان باقی مانده'
                        ],
                        [
                            'value' => 'expensive',
                            'title' => 'گرانترین'
                        ],
                        [
                            'value' => 'cheap',
                            'title' => 'ارزانترین'
                        ],
                    ],
                    'attributes' => 'required'
                ],
                [
                    'title'      => 'تعداد',
                    'key'        => 'number',
                    'input-type' => 'input',
                    'type'       => 'number',
                    'class'      => 'col-md-4 col-6',
                    'default'    => '10',
                    'attributes' => 'required'
                ],
                [
                    'title'      => 'انتخاب دسته بندی ها (اختیاری)',
                    'key'        => 'categories',
                    'input-type' => 'categories',
                    'class'      => 'col-md-9',
                ],
                [
                    'title'      => 'شامل محصولات زیر دسته ها',
                    'key'        => 'sub_category_auction',
                    'input-type' => 'select',
                    'class'      => 'col-md-3',
                    'options'    => [
                        [
                            'value' => 'yes',
                            'title' => 'بله'
                        ],
                        [
                            'value' => 'no',
                            'title' => 'خیر'
                        ]
                    ],
                ],
                [
                    'title'      => 'انتخاب دوره زمانی (اختیاری)',
                    'key'        => 'historical_period',
                    'input-type' => 'historical_period',
                    'class'      => 'col-md-6',
                ],
                [
                    'title'      => 'انتخاب اصالت (اختیاری)',
                    'key'        => 'originality',
                    'input-type' => 'originality',
                    'class'      => 'col-md-6',
                ],
                [
                    'title'      => 'انتخاب وضعیت کالا (اختیاری)',
                    'key'        => 'condition',
                    'input-type' => 'condition',
                    'class'      => 'col-md-6',
                ],
                [
                    'title'      => 'انتخاب منطقه زمانی (اختیاری)',
                    'key'        => 'timezone',
                    'input-type' => 'timezone',
                    'class'      => 'col-md-6',
                ],
                [
                    'title'      => 'نمایش فقط مزایدات تضمینی (اختیاری)',
                    'key'        => 'show_only_guaranteed',
                    'input-type' => 'select',
                    'class'      => 'col-md-3',
                    'options'    => [
                        [
                            'value' => '',
                            'title' => 'انتخاب کنید...'
                        ],
                        [
                            'value' => 'yes',
                            'title' => 'بله'
                        ],
                        [
                            'value' => 'no',
                            'title' => 'خیر'
                        ]
                    ],
                ],
                [
                    'title'      => 'لینک',
                    'key'        => 'link',
                    'input-type' => 'input',
                    'type'       => 'text',
                    'class'      => 'col-6',
                ],
                [
                    'title'      => 'عنوان لینک',
                    'key'        => 'link_title',
                    'input-type' => 'input',
                    'type'       => 'text',
                    'class'      => 'col-md-3 col-6',
                ],
            ],
            'rules' => [
                'sort_type'        => 'required|in:latest,sell',
                'link'             => 'nullable|string',
                'link_title'       => 'nullable|string',
                'number'           => 'required',
                'categories'       => 'nullable|array',
                'categories.*'     => 'nullable|exists:categories,id',
                'originality'      => 'nullable|exists:originality,id',
                'historical_period'     => 'nullable|exists:historical_periods,id',
            ]
        ],
        'blog_posts' => [
            'title' => 'مطالب وبلاگ',
            'options' => [
                [
                    'title' => 'تعداد قابل نمایش',
                    'key' => 'number',
                    'input-type' => 'input',
                    'type' => 'number',
                    'default' => '10',
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
