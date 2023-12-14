<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum NotificationSettingKeyEnum: int
{
    use EnumTrait;

    // seller
    case order_paid = 1;
    case auction_end = 2;
    case auction_before_end = 3;
    case auction_first_bid = 13;
    case auction_accept = 14;
    case auction_reject = 15;

    // buyer
    case thanks_for_buy = 4;
    case winning_auction = 5;
    case auction_higher_bid = 6;
    case followed_auction = 7;

    // system
    case events = 8;
    case news = 9;
    case favorites = 10;
    case transactions = 11;
    case discounts = 12;
    case auction_create = 16;
}
