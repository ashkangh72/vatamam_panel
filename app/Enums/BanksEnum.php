<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum BanksEnum: string
{
    use EnumTrait;

    case melli = 'بانک ملی ایران';
    case sepah = 'بانک سپه';
    case sanatmadan = 'بانک صنعت و معدن';
    case keshavarzi = 'بانک کشاورزی';
    case maskan = 'بانک مسکن';
    case toseesaderat = 'بانک توسعه صادرات ایران';
    case toseetaavon = 'بانک توسعه تعاون';
    case post = 'پست بانک ایران';

    case eghtesadnovin = 'بانک اقتصاد نوین';
    case parsian = 'بانک پارسیان';
    case karafarin = 'بانک کارآفرین';
    case saman = 'بانک سامان';
    case sina = 'بانک سینا';
    case khavarmiane = 'بانک خاورمیانه';
    case shahr = 'بانک شهر';
    case day = 'بانک دی';
    case saderat = 'بانک صادرات';
    case mellat = 'بانک ملت';
    case tejarat = 'بانک تجارت';
    case refah = 'بانک رفاه';
    case hekmatiranian = 'بانک حکمت ایرانیان';
    case gardeshgari = 'بانک گردشگری';
    case iranzamin = 'بانک ایران زمین';
    case ghavamin = 'بانک قوامین';
    case ansar = 'بانک انصار';
    case sarmayeh = 'بانک سرمایه';
    case pasargad = 'بانک پاسارگاد';
    case iranvenezuela = 'بانک مشترک ایران ونزوئلا';
    case ayandeh = 'بانک آینده';

    case mehreiran = 'بانک مهر ایران';
    case resalat = 'بانک قرض الحسنه رسالت';
}
