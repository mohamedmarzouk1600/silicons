<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FilterModal extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('admin.Templates.'.config('settings.template').'._partial.filtermodal');
    }
}
