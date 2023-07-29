<?php

use Carbon\Carbon;

/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 10/6/2018 9:51 PM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */
function ArabicDate($date)
{
    $date = date('N-j-n-Y', strtotime($date));
    list($weekday, $day, $month, $year) = explode('-', $date);
    $weekdays = array('','الأثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت','الأحد');
    $months = array('','يناير','فبراير','مارس','أبريل','مايو','يونيه','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر');
    return $weekdays[$weekday].' '.$day.' '.$months[$month].' '.$year;
}

/**
 * @param $datetime
 * @param $timezone
 * @param $format
 * @return bool|Carbon
 */

function toUTC2($datetime, $format): bool|Carbon
{
    $timezone = auth()->user()->timezone ?? 'Africa/Cairo';
    return Carbon::createFromFormat($format, Carbon::createFromTimestamp($datetime, $timezone)->format($format), $timezone)->setTimezone('UTC');
}

function toUTC($datetime, $timezone, $format): bool|Carbon
{
    return Carbon::createFromFormat($format, $datetime, $timezone)->setTimezone('UTC');
}

function toTimezone($datetime, $timezone, $format): bool|Carbon
{
    return Carbon::createFromFormat($format, $datetime, 'UTC')->setTimezone($timezone);
}

function timestampToUTC($timestamp, $timezone): bool|Carbon
{
    return Carbon::createFromTimestamp($timestamp, $timezone)->setTimezone('UTC');
}

function timestampToTimezone($timestamp, $timezone): bool|Carbon
{
    return Carbon::createFromTimestamp($timestamp, 'UTC')->setTimezone($timezone);
}

/**
 * @param $datetime
 * @param $timezone
 * @param $format
 * @return string
 */
function toUTCTime($datetime, $timezone, $format): string
{
    return Carbon::parse($datetime, $timezone)->setTimezone('UTC')->format($format);
}

/**
 * @param $datetime
 * @param $timezone
 * @param $format
 * @return string
 */
function toTimezoneTime($datetime, $timezone, $format): string
{
    return Carbon::parse($datetime, 'UTC')->setTimezone($timezone)->format($format);
}

/**
 * @param $format
 * @param $attribute
 * @return string
 */
function dateTimeSpecificFormat($format, $attribute): string
{
    return date($format, strtotime($attribute));
}

/**
 * @param $format
 * @param $attribute
 * @return string
 */
function formatFromTimestamp($attribute, $format): string
{
    return Carbon::createFromTimestamp($attribute, auth()->user()->timezone ?? 'Africa/Cairo')->format($format);
}
