<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum WidgetKeyEnum: int
{
    use EnumTrait;

    case mobile_slider = 1;
    case desktop_slider = 2;
    case service_slider = 3;
    case customer_slider = 4;
    case statistic_slider = 5;
    case auction = 6;
    case amazing_offer = 7;
    case instant_offer = 8;
    case blog_posts = 9;
}
