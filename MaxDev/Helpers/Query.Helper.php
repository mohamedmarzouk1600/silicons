<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 10/6/2018 9:53 PM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

function WhereBetween(&$eloquentData, $created_at1, $created_at2, $columnName = 'created_at')
{
    $start = \Carbon\Carbon::parse($created_at1)->startOfDay();
    $end = \Carbon\Carbon::parse($created_at2)->endOfDay();
    if (!$created_at2) {
        $eloquentData->where($columnName, '>=', $start);
    } elseif (!$created_at1) {
        $eloquentData->where($columnName, '<=', $end);
    } else {
        $eloquentData->whereBetween($columnName, [$start, $end]);
    }
}


function castToJson($json)
{
    // Convert from array to json and add slashes, if necessary.
    if (is_array($json)) {
        $json = addslashes(json_encode($json));
    } // Or check if the value is malformed.
    elseif (is_null($json) || is_null(json_decode($json))) {
        throw new \Exception('A valid JSON string was not provided.');
    }
    return \DB::raw("CAST('{$json}' AS JSON)");
}


function GetAllTranslations($Model, $Column)
{
    $names = [];
    foreach (config('app.locales') as $locale) {
        $names[] = '<b>' . __(strtoupper($locale)) . '</b>: ' . $Model->getTranslation($Column, $locale);
    }
    return implode('<hr>', $names);
}

function GetOptionsOfQuestion($optionsData)
{
    $options = [];
    foreach ($optionsData as $option) {
        $options[] = GetAllTranslations($option, 'text');
    }

    if (count($options) < 2) {
        return implode($options);
    }

    return implode('<hr>', $options);
}
