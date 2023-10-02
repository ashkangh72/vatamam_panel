<?php

// helper functions

use Carbon\Carbon;
use App\Jobs\LogSmsJob;
use App\Enums\UserCountryEnum;
use App\Channels\{PushChannel, SmsChannel};
use App\Models\{User, Option, UserOption, Viewer};
use App\Services\{FarazSms, KaveNegar, NajvaService};
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\{Artisan, Cache, Route};
use Illuminate\Support\Str;

/* add active class to li */

function active_class($route_name, $class = 'active')
{
    $group_route_name = Str::endsWith($route_name, '.*') ? Str::replaceLast('.*', '', $route_name) : $route_name;

    return Route::is($group_route_name) || Route::is($route_name) ? $class : '';
}

function open_class($route_list, $class = 'open')
{
    $text = '';

    foreach ($route_list as $route) {
        if (Route::is($route)) {
            $text = $class;
            break;
        }
    }

    return $text;
}

function option_update($option_name, $option_value): void
{
    $option = Option::firstOrNew(['name' => $option_name]);

    $option->value = $option_value;
    $option->save();

    Cache::forever('options.' . $option_name, $option_value);
}

function option($option_name, $default_value = '')
{
    $value = Cache::rememberForever('options.' . $option_name, function () use ($option_name, $default_value) {
        $option = Option::where('name', $option_name)->first();

        if ($option) {
            return is_null($option->value) ? false : $option->value;
        }

        return $default_value;
    });

    if (is_null($value) || $value === false) {
        return $default_value;
    }

    return $value;
}

function user_option_update($option_name, $option_value, $user_id = null): void
{
    if (!$user_id) {
        $user_id = auth()->user()->id;
    }

    $option = UserOption::firstOrNew([
        'option_name' => $option_name,
        'user_id' => $user_id
    ]);

    $option->option_value = $option_value;
    $option->save();
}

function user_option($option_name, $default_value = '', $user_id = null)
{
    if (!$user_id) {
        if (!auth()->check()) {
            return $default_value;
        }

        $user_id = auth()->user()->id;
    }

    $option = UserOption::where('option_name', $option_name)->where('user_id', $user_id)->first();

    return $option ? $option->option_value : $default_value;
}

function short_content($str, $words = 20, $strip_tags = true)
{
    if ($strip_tags) {
        $str = strip_tags($str);
    }

    return Str::words($str, $words);
}

function viewers_data($number = 7)
{
    $data = [];

    for ($i = 0; $i < $number; $i++) {
        $date = Carbon::now()->subDays($i);

        $views = Viewer::whereDate('created_at', $date)->count();

        $data[verta($date)->format('l')] = $views;
    }

    return $data;
}

function ip_data($number = 7)
{
    $data = [];

    for ($i = 0; $i < $number; $i++) {
        $date = Carbon::now()->subDays($i);

        $views = Viewer::whereDate('created_at', $date)->distinct('ip')->count();

        $data[verta($date)->format('l')] = $views;
    }

    return $data;
}

function array_to_string($array)
{
    $comma_separated = implode("','", $array);
    $comma_separated = "'" . $comma_separated . "'";
    return $comma_separated;
}

function convert2english($string)
{
    $newNumbers = range(0, 9);
    // 1. Persian HTML decimal
    $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
    // 2. Arabic HTML decimal
    $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
    // 3. Arabic Numeric
    $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
    // 4. Persian Numeric
    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

    $string = str_replace($persianDecimal, $newNumbers, $string);
    $string = str_replace($arabicDecimal, $newNumbers, $string);
    $string = str_replace($arabic, $newNumbers, $string);
    return str_replace($persian, $newNumbers, $string);
}

function carbon($string)
{
    return Carbon::createFromFormat('Y-m-d H:i:s', $string, 'Asia/Tehran')->timestamp;
}

function datatable($request, $query)
{
    $page = 1;

    if ($request->pagination && isset($request->pagination['page'])) {
        $page = $request->pagination['page'];
    }

    $columns = ['*'];
    $pageName = 'page';
    $perPage = 10;

    if ($request->pagination && isset($request->pagination['perpage']) && $request->pagination['perpage'] > 0) {
        $perPage = $request->pagination['perpage'];
    }

    if ($query->paginate($perPage, $columns, $pageName, $page)->lastPage() >= $page) {
        return $query->paginate($perPage, $columns, $pageName, $page);
    } else {
        return $query->paginate($perPage, $columns, $pageName, 1);
    }
}

function tverta($date)
{
    return verta($date)->timezone(config('app.timezone'));
}

function todayVerta()
{
    return verta()->timezone(config('app.timezone'));
}

function change_env($key, $value)
{
    // Read .env-file
    $env = file_get_contents(base_path() . '/.env');

    // Split string on every " " and write into array
    $env = preg_split('/\s+/', $env);

    $key_exists = false;

    // Loop through .env-data
    foreach ($env as $env_key => $env_value) {

        // Turn the value into an array and stop after the first split
        // So it's not possible to split e.g. the App-Key by accident
        $entry = explode("=", $env_value, 2);

        // Check, if new key fits the actual .env-key
        if ($entry[0] == $key) {
            // If yes, overwrite it with the new one
            $env[$env_key] = $key . "=" . $value;
            $key_exists = true;
        } else {
            // If not, keep the old one
            $env[$env_key] = $env_value;
        }
    }

    if (!$key_exists) {
        $env[] = $key . "=" . $value;
    }

    // Turn the array back to an String
    $env = implode("\n", $env);

    // And overwrite the .env with the new data
    file_put_contents(base_path() . '/.env', $env);

    Artisan::call('config:cache');
}

function sluggable_helper_function($string, $separator = '-')
{
    $_transliteration = [
        "/ö|œ/" => "e",
        "/ü/" => "e",
        "/Ä/" => "e",
        "/Ü/" => "e",
        "/Ö/" => "e",
        "/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ/" => "",
        "/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª/" => "",
        "/Ç|Ć|Ĉ|Ċ|Č/" => "",
        "/ç|ć|ĉ|ċ|č/" => "",
        "/Ð|Ď|Đ/" => "",
        "/ð|ď|đ/" => "",
        "/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/" => "",
        "/è|é|ê|ë|ē|ĕ|ė|ę|ě/" => "",
        "/Ĝ|Ğ|Ġ|Ģ/" => "",
        "/ĝ|ğ|ġ|ģ/" => "",
        "/Ĥ|Ħ/" => "",
        "/ĥ|ħ/" => "",
        "/Ì|Í|Î|Ï|Ĩ|Ī| Ĭ|Ǐ|Į|İ/" => "",
        "/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı/" => "",
        "/Ĵ/" => "",
        "/ĵ/" => "",
        "/Ķ/" => "",
        "/ķ/" => "",
        "/Ĺ|Ļ|Ľ|Ŀ|Ł/" => "",
        "/ĺ|ļ|ľ|ŀ|ł/" => "",
        "/Ñ|Ń|Ņ|Ň/" => "",
        "/ñ|ń|ņ|ň|ŉ/" => "",
        "/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ/" => "",
        "/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º/" => "",
        "/Ŕ|Ŗ|Ř/" => "",
        "/ŕ|ŗ|ř/" => "",
        "/Ś|Ŝ|Ş|Ș|Š/" => "",
        "/ś|ŝ|ş|ș|š|ſ/" => "",
        "/Ţ|Ț|Ť|Ŧ/" => "",
        "/ţ|ț|ť|ŧ/" => "",
        "/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/" => "",
        "/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ/" => "",
        "/Ý|Ÿ|Ŷ/" => "",
        "/ý|ÿ|ŷ/" => "",
        "/Ŵ/" => "",
        "/ŵ/" => "",
        "/Ź|Ż|Ž/" => "",
        "/ź|ż|ž/" => "",
        "/Æ|Ǽ/" => "E",
        "/ß/" => "s",
        "/Ĳ/" => "J",
        "/ĳ/" => "j",
        "/Œ/" => "E",
        "/ƒ/" => "",
    ];
    $quotedReplacement = preg_quote($separator, '/');
    $merge = [
        '/[^\s\p{Zs}\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
        '/[\s\p{Zs}]+/mu' => $separator,
        sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
    ];
    $map = $_transliteration + $merge;
    unset($_transliteration);
    return preg_replace(array_keys($map), array_values($map), $string);
}

function convertPersianToEnglish($string): string
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    return str_replace($persian, $english, $string);
}

function convert_number($number)
{
    $ones = array(
        "",
        "یک",
        'دو',
        "سه",
        "چهار",
        "پنج",
        "شش",
        "هفت",
        "هشت",
        "نه",
        "ده",
        "یازده",
        "دوازده",
        "سیزده",
        "چهارده",
        "پانزده",
        "شانزده",
        "هفده",
        "هجده",
        "نونزده"
    );
    $tens = array(
        "", "", "بیست", "سی", "چهل", "پنجاه", "شصت", "هفتاد", "هشتاد", "نود"
    );
    $tows = array(
        "", "صد", "دویست", "سیصد", "چهار صد", "پانصد", "ششصد", "هفتصد", "هشت صد", "نه صد"
    );

    if (($number < 0) || ($number > 999999999)) {
        throw new Exception("Number is out of range");
    }
    $Gn = floor($number / 1000000);
    /* Millions (giga) */
    $number -= $Gn * 1000000;
    $kn = floor($number / 1000);
    /* Thousands (kilo) */
    $number -= $kn * 1000;
    $Hn = floor($number / 100);
    /* Hundreds (hecto) */
    $number -= $Hn * 100;
    $Dn = floor($number / 10);
    /* Tens (deca) */
    $n = $number % 10;
    /* Ones */
    $res = "";
    if ($Gn) {
        $res .= convert_number($Gn) . " میلیون و ";
    }
    if ($kn) {
        $res .= (empty($res) ? "" : " ") . convert_number($kn) . " هزار و";
    }
    if ($Hn) {
        $res .= (empty($res) ? "" : " ") . $tows[$Hn] . " و ";
    }
    if ($Dn || $n) {
        if (!empty($res)) {
            $res .= "";
        }
        if ($Dn < 2) {
            $res .= $ones[$Dn * 10 + $n];
        } else {
            $res .= $tens[$Dn];
            if ($n) {
                $res .= " و " . $ones[$n];
            }
        }
    }
    if (empty($res)) {
        $res = "صفر";
    }

    return rtrim($res, " و");
}

function convertToPersianCharacter(string $string): string
{
    return str_replace(
        ['ك', 'دِ', 'بِ', 'زِ', 'ذِ', 'شِ', 'سِ', 'ى', 'ي', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩', '٠'],
        ['ک', 'د', 'ب', 'ز', 'ذ', 'ش', 'س', 'ی', 'ی', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '۰'],
        $string
    );
}

function formatPriceUnits($price)
{
    if ($price >= 1000000000) {
        $price = number_format($price / 1000000000, 2) . ' میلیارد';
    } elseif ($price >= 1000000) {
        $price = number_format($price / 1000000, 2) . ' میلیون';
    } elseif ($price >= 1000) {
        $price = number_format($price / 1000, 2) . ' هزار';
    }

    return $price;
}

function get_option_property($obj, $property)
{
    $obj = json_decode($obj);

    if (!is_object($obj)) {
        return null;
    }

    if (property_exists($obj, $property)) {
        return $obj->$property;
    }

    return null;
}


/**
 * @param string $message
 * @param User $user
 * @param string $type
 * @throws GuzzleException
 */
function sendSms(string $message, User $user, string $type): void
{
    $userCountry = UserCountryEnum::from($user->country);
    if ($userCountry == UserCountryEnum::iran) {
        if ($user->smsBox->balance < 500) return;

        $response = FarazSms::sendSms($message, [getCountryCode($userCountry) . $user->phone]);
        $delay = 10;
    } else {
        if ($user->smsBox->balance < 15000) return;

        $response = KaveNegar::sendSms($message, [getCountryCode($userCountry) . $user->phone]);
        $delay = 30;
    }

    if (!$response) return;

    dispatch(new LogSmsJob($user, $response, request()->ip(), $type))
        ->onQueue('sms')
        ->delay(Carbon::now()->addMinutes($delay));
}

/**
 * @param UserCountryEnum $country
 * @return string
 */
function getCountryCode(UserCountryEnum $country): string
{
    return match ($country) {
        UserCountryEnum::iran => '98',
        UserCountryEnum::iraq => '00964',
        UserCountryEnum::emirates => '00971',
        UserCountryEnum::turkey => '0090',
        UserCountryEnum::england => '0044',
    };
}

/**
 * @param array $data
 * @param array $subscribers
 * @throws GuzzleException
 */
function sendPush(array $data, array $subscribers): void
{
    NajvaService::send($data, $subscribers);
}

/**
 * @param User $user
 * @param array $channels
 * @param $key
 * @return array
 */
function notificationChannels(User $user, array $channels, $key): array
{
    $notifiableNotificationSetting = $user->notificationSettings
        ->where('key', $key)
        ->first();

    if (!$notifiableNotificationSetting) return $channels;

    if ($user->email && $notifiableNotificationSetting->email) $channels[] = 'mail';
    if ($user->phone && $notifiableNotificationSetting->sms) $channels[] = SmsChannel::class;
    if ($user->push_token && $notifiableNotificationSetting->push) $channels[] = PushChannel::class;

    return $channels;
}
