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

                'partners' => 'مدیریت همکاران',
                'partners.accept' => 'تایید همکار',
                'partners.reject' => 'رد همکار',

                'wallets.show' => 'مشاهده کیف پول',
                'wallets.create' => 'افزایش/کاهش موجودی کیف پول',
                'wallets.history.show' => 'مشاهده تراکنش های کیف پول',
                'wallets.checkouts' => 'مدیریت برداشت ها',
                'wallets.checkouts.accept' => 'تایید برداشت',
                'wallets.checkouts.reject' => 'رد برداشت'
            ]
        ],

        'auctions' => [
            'title' => 'مدیریت مزایده ها',
            'values' => [
                'index' => 'لیست مزایده ها',
                'approve' => 'تایید مزایده',
                'reject' => 'رد مزایده',
                'delete' => 'حذف مزایده'
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

        'commission_tariffs' => [
            'title' => 'مدیریت تعرفه های کمیسیون',
            'values' => [
                'index' => 'لیست تعرفه ها',
                'create' => 'ایجاد تعرفه',
                'update' => 'ویرایش تعرفه',
                'delete' => 'حذف تعرفه'
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

        'statistics' => [
            'title' => 'گزارشات',
            'values' => [
                'orders' => 'سفارشات',
                'users' => 'کاربران',
                'sms' => 'لاگ پیامک های ارسالی',
            ]
        ],

        'orders' => [
            'title' => 'مدیریت سفارشات',
            'values' => [
                'index' => 'لیست سفارشات',
                'view' => 'مشاهده سفارش',
                'refund.payment' => 'بازگردانی مبلغ پرداختی',
                'refund.accept' => 'تایید مرجوعی',
                'refund.reject' => 'رد مرجوعی',
            ]
        ],

        'settings' => [
            'title' => 'تنظیمات',
            'values' => [
                'information' => 'اطلاعات سایت',
                'socials' => 'شبکه های اجتماعی',
                'sms' => 'تنظیمات پیامک',
            ]
        ],

        'file-manager' => 'مدیریت فایل ها',
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
        [
            'name' => 'گروه چهارم',
            'key' => 4,
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
        'statistics' => [
            'title' => 'ردیف آمار',
            'options' => [
                // first row
                [
                    'title' => 'آمار اول',
                    'key' => 'statistics1',
                    'input-type' => 'input',
                    'type' => 'number',
                    'class' => 'col-md-2 col-6',
                    'default' => '0',
                    'attributes' => 'required'
                ],
                [
                    'title' => 'عنوان اول',
                    'key' => 'title1',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-md-4 col-6',
                    'attributes' => 'required'
                ],
                [
                    'title' => 'پس زمینه اول',
                    'key' => 'background1',
                    'input-type' => 'file',
                    'type' => 'file',
                    'class' => 'col-md-3 col-6',
                    'attributes' => 'required accept="image/*"',
                    'help' => 'بهترین اندازه 500 * 500'
                ],
                [
                    'title' => 'آیکن اول',
                    'key' => 'icon1',
                    'input-type' => 'file',
                    'type' => 'file',
                    'class' => 'col-md-3 col-6',
                    'attributes' => 'required accept="image/*"',
                    'help' => 'بهترین اندازه 200 * 200'
                ],
                // second row
                [
                    'title' => 'آمار دوم',
                    'key' => 'statistics2',
                    'input-type' => 'input',
                    'type' => 'number',
                    'class' => 'col-md-2 col-6',
                    'default' => '0',
                    'attributes' => 'required'
                ],
                [
                    'title' => 'عنوان دوم',
                    'key' => 'title2',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-md-4 col-6',
                    'attributes' => 'required'
                ],
                [
                    'title' => 'پس زمینه دوم',
                    'key' => 'background2',
                    'input-type' => 'file',
                    'type' => 'file',
                    'class' => 'col-md-3 col-6',
                    'attributes' => 'required accept="image/*"',
                    'help' => 'بهترین اندازه 500 * 500'
                ],
                [
                    'title' => 'آیکن دوم',
                    'key' => 'icon2',
                    'input-type' => 'file',
                    'type' => 'file',
                    'class' => 'col-md-3 col-6',
                    'attributes' => 'required accept="image/*"',
                    'help' => 'بهترین اندازه 200 * required 200'
                ],
                // third row
                [
                    'title' => 'آمار سوم',
                    'key' => 'statistics3',
                    'input-type' => 'input',
                    'type' => 'number',
                    'class' => 'col-md-2 col-6',
                    'default' => '0',
                    'attributes' => 'required'
                ],
                [
                    'title' => 'عنوان سوم',
                    'key' => 'title3',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'required col-md-4 col-6',
                    'attributes' => 'required'
                ],
                [
                    'title' => 'پس زمینه سوم',
                    'key' => 'background3',
                    'input-type' => 'file',
                    'type' => 'file',
                    'class' => 'col-md-3 col-6',
                    'attributes' => 'required accept="image/*"',
                    'help' => 'بهترین اندازه 500 * 500'
                ],
                [
                    'title' => 'آیکن سوم',
                    'key' => 'icon3',
                    'input-type' => 'file',
                    'type' => 'file',
                    'class' => 'col-md-3 col-6',
                    'attributes' => 'required accept="image/*"',
                    'help' => 'بهترین اندازه 200 * 200'
                ],
            ],
            'rules' => [
                'statistics*' => 'required|numeric',
                'title*' => 'required|string',
                'background*' => 'required|image',
                'icon*' => 'required|image',
            ]
        ],
        'auction' => [
            'title' => 'ردیف مزایدات',
            'options' => [
                [
                    'title' => 'نوع مرتب سازی',
                    'key' => 'sort_type',
                    'input-type' => 'select',
                    'class' => 'col-md-4 col-6',
                    'options' => [
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
                    'title' => 'تعداد',
                    'key' => 'number',
                    'input-type' => 'input',
                    'type' => 'number',
                    'class' => 'col-md-4 col-6',
                    'default' => '10',
                    'attributes' => 'required'
                ],
                [
                    'title' => 'انتخاب دسته بندی ها (اختیاری)',
                    'key' => 'categories',
                    'input-type' => 'categories',
                    'class' => 'col-md-9',
                ],
                [
                    'title' => 'شامل محصولات زیر دسته ها',
                    'key' => 'sub_category_auction',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'انتخاب دوره زمانی (اختیاری)',
                    'key' => 'historical_period',
                    'input-type' => 'historical_period',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'انتخاب اصالت (اختیاری)',
                    'key' => 'originality',
                    'input-type' => 'originality',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'انتخاب وضعیت کالا (اختیاری)',
                    'key' => 'condition',
                    'input-type' => 'condition',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'انتخاب منطقه زمانی (اختیاری)',
                    'key' => 'timezone',
                    'input-type' => 'timezone',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'نمایش فقط مزایدات تضمینی (اختیاری)',
                    'key' => 'show_only_guaranteed',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'نمایش فقط مزایدات فعال (اختیاری)',
                    'key' => 'show_only_not_ended',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'لینک',
                    'key' => 'link',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-6',
                ],
                [
                    'title' => 'عنوان لینک',
                    'key' => 'link_title',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-md-3 col-6',
                ],
            ],
            'rules' => [
                'sort_type' => 'required|in:latest,last_end_at,end_at,expensive,cheap',
                'link' => 'nullable|string',
                'link_title' => 'nullable|string',
                'number' => 'required',
                'categories' => 'nullable|array',
                'categories.*' => 'nullable|exists:categories,id',
                'originality' => 'nullable|exists:originality,id',
                'historical_period' => 'nullable|exists:historical_periods,id',
            ]
        ],
        'suggested_auction' => [
            'title' => 'ردیف پیشنهادات وتمام',
            'options' => [
                [
                    'title' => 'نوع مرتب سازی',
                    'key' => 'sort_type',
                    'input-type' => 'select',
                    'class' => 'col-md-4 col-6',
                    'options' => [
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
                    'title' => 'تعداد',
                    'key' => 'number',
                    'input-type' => 'input',
                    'type' => 'number',
                    'class' => 'col-md-4 col-6',
                    'default' => '10',
                    'attributes' => 'required'
                ],
                [
                    'title' => 'انتخاب دسته بندی ها (اختیاری)',
                    'key' => 'categories',
                    'input-type' => 'categories',
                    'class' => 'col-md-9',
                ],
                [
                    'title' => 'شامل محصولات زیر دسته ها',
                    'key' => 'sub_category_auction',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'انتخاب دوره زمانی (اختیاری)',
                    'key' => 'historical_period',
                    'input-type' => 'historical_period',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'انتخاب اصالت (اختیاری)',
                    'key' => 'originality',
                    'input-type' => 'originality',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'انتخاب وضعیت کالا (اختیاری)',
                    'key' => 'condition',
                    'input-type' => 'condition',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'انتخاب منطقه زمانی (اختیاری)',
                    'key' => 'timezone',
                    'input-type' => 'timezone',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'نمایش فقط مزایدات تضمینی (اختیاری)',
                    'key' => 'show_only_guaranteed',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'نمایش فقط مزایدات فعال (اختیاری)',
                    'key' => 'show_only_not_ended',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'لینک',
                    'key' => 'link',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-6',
                ],
                [
                    'title' => 'عنوان لینک',
                    'key' => 'link_title',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-md-3 col-6',
                ],
            ],
            'rules' => [
                'sort_type' => 'required|in:latest,last_end_at,end_at,expensive,cheap',
                'link' => 'nullable|string',
                'link_title' => 'nullable|string',
                'number' => 'required',
                'categories' => 'nullable|array',
                'categories.*' => 'nullable|exists:categories,id',
                'originality' => 'nullable|exists:originality,id',
                'historical_period' => 'nullable|exists:historical_periods,id',
            ]
        ],
        'amazing_offer' => [
            'title' => 'ردیف پیشنهاد شگفت انگیز',
            'options' => [
                [
                    'title' => 'نوع مرتب سازی',
                    'key' => 'sort_type',
                    'input-type' => 'select',
                    'class' => 'col-md-4 col-6',
                    'options' => [
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
                    'title' => 'تعداد',
                    'key' => 'number',
                    'input-type' => 'input',
                    'type' => 'number',
                    'class' => 'col-md-4 col-6',
                    'default' => '10',
                    'attributes' => 'required'
                ],
                [
                    'title' => 'انتخاب مزایده (اختیاری)',
                    'key' => 'auctions',
                    'input-type' => 'auctions',
                    'class' => 'col-md-12',
                ],
                [
                    'title' => 'انتخاب دسته بندی ها (اختیاری)',
                    'key' => 'categories',
                    'input-type' => 'categories',
                    'class' => 'col-md-9',
                ],
                [
                    'title' => 'شامل محصولات زیر دسته ها',
                    'key' => 'sub_category_auction',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'انتخاب دوره زمانی (اختیاری)',
                    'key' => 'historical_period',
                    'input-type' => 'historical_period',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'انتخاب اصالت (اختیاری)',
                    'key' => 'originality',
                    'input-type' => 'originality',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'انتخاب وضعیت کالا (اختیاری)',
                    'key' => 'condition',
                    'input-type' => 'condition',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'انتخاب منطقه زمانی (اختیاری)',
                    'key' => 'timezone',
                    'input-type' => 'timezone',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'نمایش فقط مزایدات تضمینی (اختیاری)',
                    'key' => 'show_only_guaranteed',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'نمایش فقط مزایدات فعال (اختیاری)',
                    'key' => 'show_only_not_ended',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'لینک',
                    'key' => 'link',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-6',
                ],
                [
                    'title' => 'عنوان لینک',
                    'key' => 'link_title',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-md-3 col-6',
                ],
            ],
            'rules' => [
                'sort_type' => 'required|in:latest,last_end_at,end_at,expensive,cheap',
                'link' => 'nullable|string',
                'link_title' => 'nullable|string',
                'number' => 'required',
                'categories' => 'nullable|array',
                'categories.*' => 'nullable|exists:categories,id',
                'originality' => 'nullable|exists:originality,id',
                'historical_period' => 'nullable|exists:historical_periods,id',
            ]
        ],
        'instant_offer' => [
            'title' => 'ردیف پیشنهاد لحظه ای',
            'options' => [
                [
                    'title' => 'نوع مرتب سازی',
                    'key' => 'sort_type',
                    'input-type' => 'select',
                    'class' => 'col-md-4 col-6',
                    'options' => [
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
                    'title' => 'تعداد',
                    'key' => 'number',
                    'input-type' => 'input',
                    'type' => 'number',
                    'class' => 'col-md-4 col-6',
                    'default' => '10',
                    'attributes' => 'required'
                ],
                [
                    'title' => 'انتخاب دسته بندی ها (اختیاری)',
                    'key' => 'categories',
                    'input-type' => 'categories',
                    'class' => 'col-md-9',
                ],
                [
                    'title' => 'شامل محصولات زیر دسته ها',
                    'key' => 'sub_category_auction',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'انتخاب دوره زمانی (اختیاری)',
                    'key' => 'historical_period',
                    'input-type' => 'historical_period',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'انتخاب اصالت (اختیاری)',
                    'key' => 'originality',
                    'input-type' => 'originality',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'انتخاب وضعیت کالا (اختیاری)',
                    'key' => 'condition',
                    'input-type' => 'condition',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'انتخاب منطقه زمانی (اختیاری)',
                    'key' => 'timezone',
                    'input-type' => 'timezone',
                    'class' => 'col-md-6',
                ],
                [
                    'title' => 'نمایش فقط مزایدات تضمینی (اختیاری)',
                    'key' => 'show_only_guaranteed',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'نمایش فقط مزایدات فعال (اختیاری)',
                    'key' => 'show_only_not_ended',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'لینک',
                    'key' => 'link',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-6',
                ],
                [
                    'title' => 'عنوان لینک',
                    'key' => 'link_title',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-md-3 col-6',
                ],
            ],
            'rules' => [
                'sort_type' => 'required|in:latest,last_end_at,end_at,expensive,cheap',
                'link' => 'nullable|string',
                'link_title' => 'nullable|string',
                'number' => 'required',
                'categories' => 'nullable|array',
                'categories.*' => 'nullable|exists:categories,id',
                'originality' => 'nullable|exists:originality,id',
                'historical_period' => 'nullable|exists:historical_periods,id',
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
                ],
                [
                    'title' => 'لینک',
                    'key' => 'link',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-6',
                ],
                [
                    'title' => 'عنوان لینک',
                    'key' => 'link_title',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-md-3 col-6',
                ],
            ],
            'rules' => [
                'number' => 'required',
                'link' => 'nullable|string',
                'link_title' => 'nullable|string',
            ]
        ],
        'poster' => [
            'title' => 'ردیف پوستر',
            'options' => [
                [
                    'title' => 'عنوان',
                    'key' => 'title',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-md-4 col-6',
                    'attributes' => 'required'
                ],
                [
                    'title' => 'پس زمینه',
                    'key' => 'background',
                    'input-type' => 'file',
                    'type' => 'file',
                    'class' => 'col-md-4 col-6',
                    'attributes' => 'required accept="image/*"',
                    'help' => 'بهترین اندازه 1080 * 1920'
                ],
            ],
            'rules' => [
                'title' => 'required|string',
                'background' => 'required|image',
            ]
        ],
        'categories' => [
            'title' => 'دسته بندی',
            'image' => 'widgets/categories.jpg',
            'options' => [
                [
                    'title'      => 'انتخاب دسته بندی ها',
                    'key'        => 'categories',
                    'input-type' => 'categories',
                    'class'      => 'col-md-12',
                ],

            ],
            'rules' => [
                'categories'      => 'required|array',
                'categories.*'    => 'exists:categories,id',
            ]
        ],
        'double_poster' => [
            'title' => 'پوستر دوتایی',
            'image' => 'widgets/banner.jpg',
            'options' => [
                [
                    'title'      => 'تعداد قابل نمایش',
                    'key'        => 'number',
                    'input-type' => 'input',
                    'type'       => 'number',
                    'default'    => '2',
                    'class'      => 'col-md-4 col-6',
                    'attributes' => 'required'
                ],
                [
                    'title'      => 'ترتیب نمایش',
                    'key'        => 'ordering',
                    'input-type' => 'select',
                    'class'      => 'col-md-4',
                    'options'    => [
                        [
                            'value' => 'asc',
                            'title' => 'صعودی'
                        ],
                        [
                            'value' => 'desc',
                            'title' => 'نزولی'
                        ]
                    ],
                ],
                [
                    'title' => 'پوستر اول',
                    'key' => 'poster1',
                    'input-type' => 'file',
                    'type' => 'file',
                    'class' => 'col-md-4 col-6',
                    'attributes' => 'required accept="image/*"',
                    'help' => 'بهترین اندازه ? * ?'
                ],
                [
                    'title' => 'پوستر دوم',
                    'key' => 'poster2',
                    'input-type' => 'file',
                    'type' => 'file',
                    'class' => 'col-md-4 col-6',
                    'attributes' => 'required accept="image/*"',
                    'help' => 'بهترین اندازه ? * ?'
                ],
                [
                    'title' => 'پوستر اول حالت موبایل',
                    'key' => 'poster1_mobile',
                    'input-type' => 'file',
                    'type' => 'file',
                    'class' => 'col-md-4 col-6',
                    'attributes' => 'required accept="image/*"',
                    'help' => 'بهترین اندازه ? * ?'
                ],
                [
                    'title' => 'پوستر دوم حالت موبایل',
                    'key' => 'poster2_mobile',
                    'input-type' => 'file',
                    'type' => 'file',
                    'class' => 'col-md-4 col-6',
                    'attributes' => 'required accept="image/*"',
                    'help' => 'بهترین اندازه ? * ?'
                ],

            ],
            'rules' => [
                'number' => 'required',
            ]
        ],
        'product' => [
            'title' => 'ردیف محصول',
            'options' => [
                [
                    'title' => 'نوع مرتب سازی',
                    'key' => 'sort_type',
                    'input-type' => 'select',
                    'class' => 'col-md-4 col-6',
                    'options' => [
                        [
                            'value' => 'latest',
                            'title' => 'جدیدترین'
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
                    'title' => 'تعداد',
                    'key' => 'number',
                    'input-type' => 'input',
                    'type' => 'number',
                    'class' => 'col-md-4 col-6',
                    'default' => '10',
                    'attributes' => 'required'
                ],
                [
                    'title' => 'انتخاب دسته بندی ها (اختیاری)',
                    'key' => 'categories',
                    'input-type' => 'categories',
                    'class' => 'col-md-9',
                ],
                [
                    'title' => 'شامل محصولات زیر دسته ها',
                    'key' => 'sub_category_auction',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'انتخاب وضعیت کالا (اختیاری)',
                    'key' => 'condition',
                    'input-type' => 'condition',
                    'class' => 'col-md-3',
                ],
                [
                    'title' => 'نمایش فقط محصولات فعال (اختیاری)',
                    'key' => 'show_only_not_ended',
                    'input-type' => 'select',
                    'class' => 'col-md-3',
                    'options' => [
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
                    'title' => 'لینک',
                    'key' => 'link',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-6',
                ],
                [
                    'title' => 'عنوان لینک',
                    'key' => 'link_title',
                    'input-type' => 'input',
                    'type' => 'text',
                    'class' => 'col-md-3 col-6',
                ],
            ],
            'rules' => [
                'sort_type' => 'required|in:latest,expensive,cheap',
                'link' => 'nullable|string',
                'link_title' => 'nullable|string',
                'number' => 'required',
                'categories' => 'nullable|array',
                'categories.*' => 'nullable|exists:categories,id',
            ]
        ],
    ],

    'sliderGroups' => [
        [
            'group' => 'desktop_slider',
            'name' => 'اسلایدر دسکتاپ',
            'width' => 1780,
            'height' => 890,
            'count' => 2,
            'size' => '890 * 1780'
        ],
        [
            'group' => 'mobile_slider',
            'name' => 'اسلایدر حالت موبایل',
            'width' => 300,
            'height' => 250,
            'count' => 5,
            'size' => '250 * 300'
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
        ]
    ],

    'socials' => [
        [
            'name' => 'تلگرام',
            'key' => 'info_telegram',
            'icon' => 'fa fa-telegram',
        ],
        [
            'name' => 'اینستاگرام',
            'key' => 'info_instagram',
            'icon' => 'feather icon-instagram',
        ],
        [
            'name' => 'توییتر',
            'key' => 'info_twitter',
            'icon' => 'fa fa-twitter',
        ],
        [
            'name' => 'فیسبوک',
            'key' => 'info_facebook',
            'icon' => 'fa fa-facebook',
        ],
        [
            'name' => 'لینکداین',
            'key' => 'info_linkedin',
            'icon' => 'fa fa-linkedin',
        ],
        [
            'name' => 'پینترست',
            'key' => 'info_pinterest',
            'icon' => 'fa fa-pinterest',
        ],
    ],
];
