<?php

namespace MaxDev\Services\AdminTemplate;

class AdminTemplateService
{
    /**
     * @param $routePrefix
     * @param $row
     * @param $type
     * @return false|mixed
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function generateButtons($routePrefix, $row, $type)
    {
        return call_user_func('MaxDev\Services\AdminTemplate\\' . config('settings.template') . 'Template::generateButtons', $routePrefix, $row, $type);
    }

    public function generateRowDropDown($dropdowns)
    {
        return call_user_func('MaxDev\Services\AdminTemplate\\'.config('settings.template').'Template::generateRowDropDown', $dropdowns);
    }
}
