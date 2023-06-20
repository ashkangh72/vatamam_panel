<?php


return [
    'theme_name' => 'DefaultTheme',

    'sliderGroups' => [
        /*[
            'group' => 'main_sliders',
            'name'  => 'اسلایدر اصلی',
            'width' => 1780,
            'height' => 890,
            'count' => 2,
            'size'  => '890 * 1780'
        ],*/
        [
            'group' => 'mobile_sliders',
            'name' => 'اسلایدر حالت موبایل',
            'width' => 300,
            'height' => 250,
            'count' => 5,
            'size' => '250 * 300'
        ],
        [
            'group' => 'page_sliders',
            'name' => 'اسلایدر صفحات',
            'width' => 300,
            'height' => 300,
            'count' => 4,
            'size' => '300 * 300'
        ],
        [
            'group' => 'coworker_sliders',
            'name' => 'اسلایدر لوگو همکاران',
            'width' => 100,
            'height' => 100,
            'count' => 3,
            'size' => '100 * 100'
        ],
        [
            'group' => 'services_sliders',
            'name' => 'اسلایدر خدمات',
            'width' => 100,
            'height' => 85,
            'count' => 3,
            'size' => '100 * 85'
        ],
    ],

    'bannerGroups' => [
        [
            'group' => 'index_middle_banners',
            'name' => 'بنر دوتایی',
            'width' => 820,
            'height' => 300,
            'count' => 2,
            'size' => '300 * 820'
        ],
        /*[
            'group' => 'index_slider_banners',
            'name' => 'بنر کنار اسلایدر اصلی',
            'width' => 856,
            'height' => 428,
            'count' => 2,
            'size' => '428 * 856'
        ],*/
        [
            'group' => 'index_unequal_banners',
            'name' => 'بنر دوتایی (کوچک-بزرگ)',
            'width' => 820,
            'height' => 450,
            'count' => 2,
            'size' => '450 * 820'
        ],
        [
            'group' => 'index_unequal_banners_reverse',
            'name' => 'بنر دوتایی (بزرگ-کوچک)',
            'width' => 820,
            'height' => 450,
            'count' => 2,
            'size' => '450 * 820'
        ],
        [
            'group' => 'index_narrow_banners',
            'name' => 'بنر باریک',
            'width' => 820,
            'height' => 150,
            'count' => 1,
            'size' => '150 * 820'
        ],
    ],

    'imageSizes' => [
        'productCategoryImage' => '500 * 500',
        'CategoryImage' => '500 * 500',
        'postImage' => '300 * 400',
        'productGalleryImage' => '720 * 1280',
        'productImage' => '600 * 600',
        'logo' => '36 * 128',
        'icon' => '32 * 32',
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

    'errors' => [
        '404' => 'front::errors.404'
    ],

    'pages' => [
        'login' => 'front::auth.login',
        'verify' => 'front::auth.verify-user',
        'register' => 'front::auth.register',
        'forgot-password' => 'front::auth.forgot-password',
        'one-time-login' => 'front::auth.one-time-login',
    ],

    'routes' => [
        'verify' => 'front.verify.showVerify',
        'change-password' => 'front.user.force-change-password',
        'change-password-routes' => ['front.user.force-change-password', 'front.user.force-update-password'],
    ],

    'exceptVerifyCsrfToken' => [
        'orders/payment/callback/*',
        'wallet/payment/callback/*',
    ],

    'asset_path' => 'themes/defaultTheme/',
    'mainfest_path' => 'themes/defaultTheme',
    'demo' => [
        'image' => 'demo/preview.jpg',
        'name' => 'قالب پیش فرض',
        'description' => 'قالب پیش فرض اسکریپت فروشگاهی '
    ],

    'socials' => [
        [
            'name' => 'تلگرام',
            'key' => 'social_telegram',
            'icon' => 'fa fa-telegram',
        ],
        [
            'name' => 'اینستاگرام',
            'key' => 'social_instagram',
            'icon' => 'feather icon-instagram',
        ],
        [
            'name' => 'واتساپ',
            'key' => 'social_whatsapp',
            'icon' => 'fa fa-whatsapp',
        ],
    ],

    'settings' => [
        'fields' => [
            [
                'title' => 'نمایش نمودار قیمت در صفحه محصول',
                'key' => 'dt_show_price_change_chart',
                'input-type' => 'select',
                'class' => 'col-md-4 col-6',
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
                'attributes' => 'required'
            ]
        ],
        'rules' => [
            'dt_show_price_change_chart' => 'required|in:yes,no'
        ]
    ],

    'links' => [
        public_path('themes/defaultTheme') => base_path('themes/DefaultTheme/src/resources/assets'),
    ],

    'home-widgets' => require __DIR__ . '/../config/widgets.php',

    'cache-forget' => [
        'categories' => [
            'front.productcats',
            'front.index.categories'
        ],
        'products' => [],
        'posts' => [],
        'sliders' => [],
        'banners' => []
    ]
];
