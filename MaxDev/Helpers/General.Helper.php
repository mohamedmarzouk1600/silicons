<?php

use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use MaxDev\Models\Call;
use MaxDev\Models\GpWaitingLogs;
use MaxDev\Models\Office;
use MaxDev\Models\Patient;
use MaxDev\Models\WorkingShift;
use MaxDev\Enums\UserGroupType;
use MaxDev\Enums\CallStatus;
use Pusher\Pusher;

/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 10/6/2018 9:53 PM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

function youtubeKey($link)
{
    preg_match(
        '/http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([\w\-\_]*)(&(amp;)?‌​[\w\?‌​=]*)?/',
        $link,
        $match
    );
    if (is_array($match) && isset($match[1])) {
        return $match[1];
    } else {
        return null;
    }
}


/**
 * @param $filePath
 * @param $text
 * @param int $linesBeforeLast
 * @return void
 */
function AppendTextBeforeLastLine($filePath, $text, int $linesBeforeLast)
{
    $fc = fopen($filePath, "r");
    while (!feof($fc)) {
        $buffer = fgets($fc, 4096);
        $lines[] = $buffer;
    }
    fclose($fc);
    $f = fopen($filePath, "w") or die("couldn't open $filePath");

    $lineCount = count($lines);
    for ($i = 0; $i < $lineCount - $linesBeforeLast; $i++) {
        fwrite($f, $lines[$i]);
    }
    if (is_array($text)) {
        foreach ($text as $oneLine) {
            fwrite($f, $oneLine . PHP_EOL);
        }
    } else {
        fwrite($f, $text . PHP_EOL);
    }

    for ($x = $linesBeforeLast; $x > 0; $x--) {
        fwrite($f, $lines[$lineCount - $x]);
    }
    fclose($f);
}

/**
 * @param $haystack
 * @param $needle
 * @return bool
 */
function startsWith($haystack, $needle)
{
    return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
}

/**
 * @param $haystack
 * @param $needle
 * @return bool
 */
function endsWith($haystack, $needle)
{
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}

/**
 * @param $guard
 * @return array|false|void
 */
function AdminHomeUrl($guard)
{
    if (Auth::guard($guard)->check()) {
        $AdminGroup = Auth()->user()->adminGroup;
        if ($AdminGroup->home_url) {
            $RouteName = recursiveFindArray(config('permissions'), $AdminGroup->home_url);
            do {
                $RouteName = $RouteName[0];
            } while (is_array($RouteName));
        }
        if (!$AdminGroup->home_url) {
            return false;
        }
        if (\Illuminate\Support\Str::contains($RouteName, ['show', 'edit', 'delete']) && $AdminGroup->url_index) {
            return [$RouteName, $AdminGroup->url_index];
        } else {
            return [$RouteName];
        }
    }
}


if (!function_exists('enum_select')) {
    /**
     * @param $enum
     * @param $default
     * @return array
     */
    function enum_select($enum, $default = true, $defaultValue=null)
    {
        $arr = [];
        foreach ($enum::getConstList() as $key => $value) {
            $arr[$value] = __($key);
        }
        if ($default) {
            $arr = array_merge($defaultValue ?? [0=>__('Select')], $arr);
        }
        return $arr;
    }
}

if (!function_exists('settings')) {
    /**
     * @param $name
     * @return mixed|string
     */
    function settings($name)
    {
        $setting = \Illuminate\Support\Facades\Cache::get('settings.' . $name);
        if (!$setting) {
            return '';
        }
        if (is_array($setting)) {
            return $setting[app()->getLocale()];
        } else {
            return $setting;
        }
    }
}

if (!function_exists('week_day_name')) {
    /**
     * @param $week_day_number
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|void|null
     */
    function week_day_name($week_day_number)
    {
        switch ($week_day_number) {
            case 1:
                return __('Monday');
            case 2:
                return __('Tuesday');
            case 3:
                return __('Wednesday');
            case 4:
                return __('Thursday');
            case 5:
                return __('Friday');
            case 6:
                return __('Saturday');
            case 7:
                return __('Sunday');
            default:
                break;
        }
    }
}


if (!function_exists('price')) {
    /**
     * @param $amount
     * @param $fraction
     * @return string
     */
    function price($amount, $fraction = false)
    {
        return number_format($amount, $fraction ? 2 : 0) . ' ' . __('LE');
    }
}

if (!function_exists('TimeMinutes')) {
    /**
     * @param $time
     * @return string
     */
    function TimeMinutes($time): string
    {
        return $time . ' ' . __('Minutes');
    }
}


if (!function_exists('mobileNumber')) {
    /**
     * @param $mobile
     * @return string
     */
    function mobileNumber($mobile): string
    {
        return substr($mobile, 2);
    }
}

if (!function_exists('exception_message')) {
    /**
     * @param Throwable $throwable
     * @return string
     */
    function exception_message(Throwable $throwable): string
    {
        return $throwable->getMessage() . ' File: ' . $throwable->getFile() . ' (' . $throwable->getLine() . ')';
    }
}


if (!function_exists('datatable_search')) {
    /**
     * @param $elquentQuery
     * @param $searchColumns
     * @param $searchWord
     * @return mixed
     */
    function datatable_search($elquentQuery, $searchColumns, $searchWord)
    {
        $elquentQuery->where(function ($query) use ($searchColumns, $searchWord) {
            foreach ($searchColumns as $key => $column) {
                if ($key === 0) {
                    if (strpos($column, '.')) {
                        list($col, $json) = explode('.', $column);
                        $query->where(
                            \Illuminate\Support\Facades\DB::raw("lower(JSON_EXTRACT($col, \"$.$json\"))"),
                            'LIKE',
                            '%' . $searchWord . '%'
                        );
                    } else {
                        $query->where(
                            \Illuminate\Support\Facades\DB::raw("lower(`$column`)"),
                            'LIKE',
                            '%' . $searchWord . '%'
                        );
                    }
                } else {
                    if (strpos($column, '.')) {
                        list($col, $json) = explode('.', $column);
                        $query->orWhere(
                            \Illuminate\Support\Facades\DB::raw("lower(JSON_EXTRACT($col, \"$.$json\"))"),
                            'LIKE',
                            '%' . $searchWord . '%'
                        );
                    } else {
                        $query->orWhere(
                            \Illuminate\Support\Facades\DB::raw("lower(`$column`)"),
                            'LIKE',
                            '%' . $searchWord . '%'
                        );
                    }
                }
            }
        });
        return $elquentQuery;
    }
}

if (!function_exists('server_env')) {
    /**
     * @param $key
     * @param $default
     * @return array|ArrayAccess|bool|mixed|null
     */
    function server_env($key, $default = null)
    {
        if (array_key_exists($key, $_SERVER)) {
            $value = \Illuminate\Support\Arr::get($_SERVER, $key);
            return match ($value) {
                'true' => true,
                'false' => false,
                'null' => null,
                default => $value,
            };
        } else {
            return Env::get($key, $default);
        }
    }
}

if (!function_exists('app_asset')) {
    /**
     * @param $path
     * @param $secure
     * @return string
     */
    function app_asset($path, $secure = null)
    {
        if (app()->environment('production')) {
            return 'https://tikshif-static.s3.amazonaws.com/' . $path;
        } elseif (app()->environment('staging')) {
            return 'https://tikshif-staging-static.s3.amazonaws.com/' . $path;
        } else {
            return app('url')->asset($path, $secure);
        }
    }
}

if (!function_exists('current_app_url')) {
    /**
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    function current_app_url()
    {
        if (config('app.env') == 'production') {
            return 'https://static.s3.amazonaws.com';
        } elseif (config('app.env') == 'staging') {
            return 'https://staging-static.s3.amazonaws.com';
        } else {
            return config('app.url');
        }
    }
}


if (!function_exists('set_patient_files_storage')) {
    /**
     * @return void
     */
    function set_patient_files_storage()
    {
        if (!app()->environment('local')) {
            \Illuminate\Support\Facades\Config::set('filesystems.default', 's3');
        }
    }
}


if (!function_exists('generate_content_case_comments')) {
    /**
     * @param $comments
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    function generate_content_case_comments($comments)
    {
        if (count($comments) > 0) {
            $result = '';

            foreach ($comments as $key => $comment) {
                if (array_key_last($comments) == $key) {

//                    if($comment['attachment_url'] != null) {
//                        $result .= '<div><a href=\'' . $comment['attachment_url'] . '\' target=\'_blank\'>' . __('Comment Attachment') . '</a></div><p>' . $comment['comment'] . '</p>';
//                    } else {
                    $result .= '<p>' . $comment['comment'] . '</p>';
//                    }
                } else {

//                    if($comment['attachment_url'] != null) {
//                        $result .= '<div><a href=\'' . $comment['attachment_url'] . ' \'>' . __('Attachment Url') . '</ahref></div><p>' . $comment['comment'] . '</p><p>--------------------------------------------------------------------------------------</p>';
//                    } else {
                    $result .= '<p>' . $comment['comment'] . '</p><p>--------------------------------------------------------------------------------------</p>';
//                    }
                }
            }

            return $result;
        }

        return __('No Comments');
    }
}



function CustomerMobile($country_code,$value)
{
    return match ($country_code) {
        '+213','+43','+20','+49','+30','+91','+98','+964','+39','+1','+81','+82','+52','+92','+63','+7','+90' => preg_match('/^\+'.substr($country_code, 1).'[0-9]{10}$/', $value),
        '+93','+355','+244','+61','+994','+973','+880','+375','+32','+359','+56','+243','+242','+420','+240','+251','+358','+33','+995','+233','+502','+36','+62','+353','+972','+962','+996','+218','+261','+265','+60','+223','+258','+977','+31','+227','+47','+48','+351','+40','+381','+65','+421','+27','+34','+94','+46','+41','+963','+66','+216','+380','+971','+44', => preg_match('/^\+'.substr($country_code, 1).'[0-9]{9}$/', $value),
        '+376','+374','+501','+229','+975','+591','+387','+267','+226','+257','+855','+237','+238','+236','+235','+57','+269','+506','+385','+53','+357','+45','+253','+593','+503','+372','+241','+220','+224','+592','+509','+504','+254','+686','+850','+965','+856','+371','+961','+266','+231','+423','+370','+352','+960','+356','+692','+222','+230','+691','+373','+377','+976','+382','+212','+95','+264','+674','+64','+505','+234','+968','+507','+675','+595','+51','+974','+250','+378','+239','+966','+221','+232','+386','+677','+252','+249','+268','+886','+992','+255','+228','+676','+993','+688','+256', => preg_match('/^\+'.substr($country_code, 1).'[0-9]{8}$/', $value),
        '+54','+55','+86' => preg_match('/^\+'.substr($country_code, 1).'[0-9]{11}$/', $value),
        '+673','+291','+679','+245','+354','+680','+685','+248','+597' => preg_match('/^\+'.substr($country_code, 1).'[0-9]{7}$/', $value),
        '+670' => preg_match('/^\+'.substr($country_code, 1).'[0-9]{7,8}$/', $value),
        default => false,
    };
    return false;
}
