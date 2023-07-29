<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 10/6/2018 9:42 PM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

return [
    'environment' => env('APP_ENV'),
    'template' => env('ADMIN_TEMPLATE', 'Stack'),
    
    'redirect_call_time' => 3, // minutes
    'timezone' => 'UTC',
    'call_cost' => 50,
    'app_home_page_slider' => 'app-home-page-slider',
    'static_url' => current_app_url(),
];
