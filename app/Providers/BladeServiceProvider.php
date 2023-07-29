<?php

namespace App\Providers;

use App\View\Components\FormInputWrapper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\ButtonModal;
use App\View\Components\FilterModal;
use App\View\Components\BreadCrumb;
use App\View\Components\FormInput;
use App\View\Components\FormModal;
use App\View\Components\Alert;
use App\View\Components\Card;
use App\View\Components\Row;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('admin-form-group-wrapper', FormInputWrapper::class);
        Blade::component('admin-filter-modal', FilterModal::class);
        Blade::component('admin-button-model', ButtonModal::class);
        Blade::component('admin-breadcrumb', BreadCrumb::class);
        Blade::component('admin-form-group', FormInput::class);
        Blade::component('admin-form-model', FormModal::class);
        Blade::component('admin-alert', Alert::class);
        Blade::component('admin-card', Card::class);
        Blade::component('admin-row', Row::class);
    }
}
