<?php

namespace MaxDev\Services\AdminTemplate;

interface TemplateInterface
{
    public static function generateButtons($routePrefix, $row, $type);
    public static function generateRowDropDown($dropdowns);
}
