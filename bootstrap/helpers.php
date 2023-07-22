<?php

// helper functions

use App\Models\Cart;
use App\Models\Category;
use App\Models\Gateway;
use App\Models\Option;
use App\Models\Product;
use App\Models\Sms;
use App\Models\Specification;
use App\Models\SpecificationGroup;
use App\Models\SpecType;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserOption;
use App\Models\Viewer;
use App\Services\SMSIR\Smsir;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/* add active class to li */

function active_class($route_name, $class = 'active')
{
    return Route::is($route_name) ? $class : '';
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

function option_update($option_name, $option_value)
{
    $option = Option::firstOrNew(['option_name' => $option_name]);

    $option->option_value = $option_value;
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

    return  $value;
}

function user_option_update($option_name, $option_value, $user_id = null): void
{
    if (!$user_id) {
        $user_id = auth()->user()->id;
    }

    $option = UserOption::firstOrNew([
        'option_name' => $option_name,
        'user_id'     => $user_id
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

// add new tags and return tags id
function addTags($tags)
{
    $tags = explode(',', $tags);
    $tags_id = [];

    foreach ($tags as $item) {
        $tag = Tag::where('name', $item)->first();
        if (!$tag) {
            $tag = Tag::create([
                'name' => $item,
            ]);
        }
        $tags_id[] = $tag->id;
    }

    return $tags_id;
}

function get_cart()
{
    $cart = null;

    if (auth()->check()) {
        $cart = Cart::where('user_id', auth()->user()->id)->first();
    } else {
        $cart_id = Cookie::get('cart_id');

        if ($cart_id) {
            $cart = Cart::where('id', $cart_id)->first();
        }
    }

    return $cart;
}

/* return true if cart products quantity is ok
 * and return false if cart products quantity is more than product stock
 */
function check_cart_quantity()
{
    $cart = get_cart();

    if (!$cart || !$cart->products()->count()) {
        return true;
    }

    foreach ($cart->products as $product) {
        $productQuantityInCart = $cart->products()->where('product_id', $product->id)->where('price_id', '!=', $product->pivot->price_id)->sum('quantity');
        $price     = $product->prices()->find($product->pivot->price_id);
        $has_stock = $price->hasStock($product->pivot->quantity, $productQuantityInCart);

        if (!$has_stock['status']) {
            return false;
        }
    }

    return true;
}

function check_cart_discount()
{
    $cart = get_cart();

    if (!$cart || !$cart->products()->count()) {
        return ['status' => true];
    }

    if ($cart->discount) {

        $status = $cart->canUseDiscount();
        return $status;
    }

    return ['status' => true];
}

function check_cart()
{
    return check_cart_quantity() && check_cart_discount()['status'];
}

//get user address
function user_address($key)
{
    if (old($key)) {
        return old($key);
    }

    return auth()->user()->address ? auth()->user()->address->$key : '';
}

function short_content($str, $words = 20, $strip_tags = true)
{
    if ($strip_tags) {
        $str = strip_tags($str);
    }

    return Str::words($str, $words);
}


function spec_type($request)
{
    if (!$request->spec_type || !$request->specification_group) {
        return null;
    }

    $spec_type = SpecType::firstOrCreate([
        'name' => $request->spec_type
    ]);

    $group_ordering = 0;

    foreach ($request->specification_group as $group) {

        if (!isset($group['specifications'])) {
            continue;
        }

        $spec_group = SpecificationGroup::firstOrCreate([
            'name' => $group['name'],
        ]);

        $specification_ordering = 0;

        foreach ($group['specifications'] as $specification) {
            $spec = Specification::firstOrCreate([
                'name' => $specification['name']
            ]);

            if (!$spec_type->specifications()->where('specification_id', $spec->id)->where('specification_group_id', $spec_group->id)->first()) {
                $spec_type->specifications()->attach([
                    $spec->id => [
                        'specification_group_id' => $spec_group->id,
                        'group_ordering'         => $group_ordering,
                        'specification_ordering' => $specification_ordering++,
                    ]
                ]);
            }
        }

        $group_ordering++;
    }

    return $spec_type->id;
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

function get_discount_price($price, $discount, $product = null): float
{

    $price = $price - ($price * ($discount / 100));

    return to_round_price($price, $product);
}

function to_round_price($price, $product): float
{
    if ($product && $product->currency) {
        $price = $price * $product->currency->amount;
    }

    if ($product) {
        $rounding_amount = $product->rounding_amount;

        if ($rounding_amount == 'default') {
            $rounding_amount = option('default_rounding_amount', 'no');
        }

        $rounding_type = $product->rounding_type;

        if ($rounding_type == 'default') {
            $rounding_type = option('default_rounding_type', 'close');
        }

        switch ($rounding_amount) {
            case "100":
            case "1000":
            case "10000":
            case "100000": {
                if ($rounding_type == 'up') {
                    $price = ceil($price / $rounding_amount) * $rounding_amount;
                } else if ($rounding_type == 'down') {
                    $price = floor($price / $rounding_amount) * $rounding_amount;
                } else {
                    $price = round($price / $rounding_amount) * $rounding_amount;
                }
                break;
            }
        }
    }

    return (float) $price;
}

function category_group($key)
{
    switch ($key) {
        case 'postcat': {
                return 'دسته بندی وبلاگ';
            }
        case 'productcat': {
                return 'دسته بندی محصول';
            }
    }
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

    $string =  str_replace($persianDecimal, $newNumbers, $string);
    $string =  str_replace($arabicDecimal, $newNumbers, $string);
    $string =  str_replace($arabic, $newNumbers, $string);
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

/**
 * Send sms to specify pattern_code and mobile number with input data.
 *
 * @param  mixed $pattern_code
 * @param  mixed $mobile
 * @param  mixed $input_data
 * @param  mixed $type
 * @param  mixed $user_id
 * @return void
 */
function sendSms($pattern_code, $mobile, $params, $type = null, $user_id = null)
{

    $response=Smsir::ultraFastSend($params, $mobile, $pattern_code);

    Sms::create([
        'mobile'     => $mobile,
        'ip'         => request()->ip(),
        'type'       => $type,
        'user_id'    => $user_id,
        'response'   => $response,
    ]);

    return $response;
}

function cart_min($selected_price)
{
    if ($selected_price->cart_min !== null) {
        return min($selected_price->cart_min, $selected_price->stock);
    }

    return min($selected_price->stock, 1);
}

function cart_max($selected_price)
{
    if ($selected_price->cart_max !== null) {
        return min($selected_price->cart_max, $selected_price->stock);
    }

    return $selected_price->stock;
}

function get_category_products_id(Category $category)
{
    $allChildCategories = $category->allChildCategories();
    $product_ids = Product::whereIn('category_id', $allChildCategories)->pluck('id');
    return $product_ids;
}

function remove_id_from_url($id)
{
    $segments = request()->segments();

    if (($key = array_search($id, $segments)) !== false) {
        unset($segments[$key]);
    }

    return url(implode('/', $segments));
}

function get_separated_values($array, $separator)
{
    if (!$separator) {
        return $array;
    }

    $result = [];

    foreach ($array as $item) {
        foreach (explode($separator, $item) as $val) {
            $result[] = trim($val);
        }
    }

    return array_unique($result);
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

function tverta($date)
{
    return verta($date)->timezone(config('app.timezone'));
}

function todayVerta()
{
    return verta()->timezone(config('app.timezone'));
}

function application_installed()
{
    return file_exists(storage_path('installed'));
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

function get_current_theme()
{
    $current_theme = config('general.current_theme');

    if (file_exists(base_path() . '/themes/' . $current_theme)) {
        $theme = [];
        $theme['name'] = $current_theme;
        $theme['service_provider'] = "Themes\\$current_theme\src\ThemeServiceProvider";
        return $theme;
    }

    return null;
}

function current_theme_name()
{
    return config('front.theme_name');
}

function customConfig($path)
{
    if (file_exists($path)) {
        $config = include $path;
        return $config;
    }
}

function str_random($length)
{
    return Str::random($length);
}

function get_svg_contents($path, $default = '')
{
    if (file_exists(public_path($path))) {

        $file_parts = pathinfo($path);

        if ($file_parts['extension'] == 'svg') {
            return file_get_contents(public_path($path));
        }
    }

    return $default;
}

function to_sql($query)
{
    return vsprintf(str_replace(['?'], ['\'%s\''], $query->toSql()), $query->getBindings());
}

function store_user_cart(User $user)
{
    $cart_id = Cookie::get('cart_id');

    if ($cart_id) {
        $cart = Cart::find($cart_id);

        if ($cart && $cart->user_id == null) {

            $user_cart = Cart::where('user_id', $user->id)->first();

            if (!$user_cart) {
                $cart->update([
                    'user_id' => $user->id,
                ]);
            } else {
                foreach ($cart->products as $product) {
                    $query = DB::table('cart_product')->where('cart_id', $user_cart->id)->where('product_id', $product->id)->where('price_id', $product->pivot->price_id);
                    $user_cart_product = $query->first();

                    if (!$user_cart_product) {

                        DB::table('cart_product')->insert([
                            'cart_id'    => $user_cart->id,
                            'product_id' => $product->id,
                            'quantity'   => $product->pivot->quantity,
                            'price_id'   => $product->pivot->price_id,
                        ]);
                    } else {

                        $query->update([
                            'quantity' => $product->pivot->quantity,
                        ]);
                    }
                }

                $cart->delete();
            }

            Cookie::queue(Cookie::forget('cart_id'));
        }
    }
}

function ellips_text($str, $char)
{
    $out = mb_strlen($str, 'utf-8') > $char ? mb_substr($str, 0, $char, 'utf-8') . "..." : $str;

    return $out;
}

function gateway_key($driver_name)
{
    if ($driver_name == 'behpardakht') {
        return 'mellat';
    }

    return $driver_name;
}

function get_gateway_configs($gateway)
{
    $gateway = Gateway::where('key', $gateway)->first();
    $configs = [];

    switch ($gateway->key) {
        case "payping":
        case "idpay":
        case "saman":
        case "payir":
        case "zarinpal":
        case "rayanpay": {
            $configs['merchantId'] = $gateway->config('merchantId');
            break;
        }
        case "behpardakht": {
            $configs['terminalId'] = $gateway->config('terminalId');
            $configs['username']   = $gateway->config('username');
            $configs['password']   = $gateway->config('password');
            break;
        }
        case "sepehr": {
            $configs['terminalId'] = $gateway->config('terminalId');
            break;
        }
    }

    return $configs;
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

function admin_route_prefix()
{
    return env('ADMIN_ROUTE_PREFIX');
}

function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
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

function convertPersianToEnglish($string)
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    $output = str_replace($persian, $english, $string);
    return $output;
}

function aparat_iframe($string)
{
    $p = '/^(?:https?:\/\/)?(?:www\.)?(?:aparat\.com\/v\/)(\w*)(?:\S+)?$/';
    preg_match($p, $string, $matches);

    if (empty($matches)) {
        return '';
    }

    return '<div class="h_iframe-aparat_embed_frame"><span style="display: block;padding-top: 57%">.</span><iframe data-src="https://www.aparat.com/video/video/embed/videohash/' . $matches[1] . '/vt/frame" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe></div>';
}

function theme_asset($path)
{
    return asset(config('front.asset_path') . $path);
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
        $res .= convert_number($Gn) .  " میلیون و ";
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
    $res = rtrim($res, " و");
    return $res;
}

function run_theme_config()
{
    if (function_exists('theme_first_config')) {
        theme_first_config();

    }
}

function convertToPersianCharacter(string $string): string
{
    return str_replace(
        ['ك', 'دِ', 'بِ', 'زِ', 'ذِ', 'شِ', 'سِ', 'ى', 'ي', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩', '٠'],
        ['ک', 'د', 'ب', 'ز', 'ذ', 'ش', 'س', 'ی', 'ی', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '۰'],
        $string
    );
}
