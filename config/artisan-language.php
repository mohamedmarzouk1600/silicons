<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 9/19/20 7:04 AM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

return [
    "scan_paths" => [
        app_path(),
        base_path('MaxDev'),
        resource_path('views'),
    ],
    "scan_pattern" => '/(@lang|__|\$t|\$tc)\s*(\(\s*[\'"])([^$]*)([\'"]+\s*(,[^\)]*)*\))/U',
    "lang_path" => resource_path('lang'),
];
