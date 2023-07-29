<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 11/15/20 8:47 PM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace App\Console\Commands;


trait MaxDevRoot
{

    protected function rootNamespace()
    {
        $this->laravel['path'] = base_path('MaxDev');
        return "MaxDev\\";
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return "{$rootNamespace}\\".$this->Dir;
    }
}
