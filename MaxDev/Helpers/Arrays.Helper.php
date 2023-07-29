<?php


use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Illuminate\Support\Arr;

function RecursiveFind($arr, $key)
{
    $val = array();
    array_walk_recursive($arr, function ($v, $k) use ($key, &$val) {
        if ($k == $key) {
            array_push($val, $v);
        }
    });
    return count($val) > 1 ? $val : array_pop($val);
}

function recursiveFindArray(array $array, $needle)
{
    $response = [];
    $iterator  = new RecursiveArrayIterator($array);
    $recursive = new RecursiveIteratorIterator(
        $iterator,
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($recursive as $key => $value) {
        if ($key === $needle) {
            $response[] = $value;
        }
    }
    return ((count($response)=='1') ? $response : $response);
}

function Array_Sum_OR_Value($data)
{
    if (is_array($data)) {
        return array_sum($data);
    } else {
        return $data;
    }
}

function array_Custom_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function getObjectsFromArray($data)
{
    $result = '';
    foreach ($data as $key => $col) {
        if (($key + 1) < count($data)) {
            $result .= '{\"data\" : \"' . $col . '\"},';
        } else {
            $result .= '{\"data\" : \"' . $col . '\"}';
        }
    }
    return $result;
}

/**
 * Get attributes from media collection.
 * @param MediaCollection $media
 * @return array
 */
function getImagesFromMedia(MediaCollection $media)
{
    $images = [];

    foreach ($media as $image) {
        $images[] = [
            'id'            => $image->id,
            'original_url'  => $image->getFullUrl(),
            'file_name'     => $image->file_name,
            'custom_properties'     => $image->custom_properties,
            'type'     => Arr::get($image->custom_properties, 'image_type', 'image'),
        ];
    }
    return $images;
}
