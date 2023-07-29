<?php

namespace MaxDev\Services\AdminTemplate;

class WellyTemplate implements TemplateInterface
{
    public static function generateButtons($routePrefix, $row, $type): array
    {
        $buttons = [];
        switch ($type) {
            case 'create':
                break;
            case 'show':
                $buttons[] = [
                    'class' =>  'fa fa-edit',
                    'link'  =>  route($routePrefix.'edit', $row->id),
                    'name'  =>  __('Edit'),
                ];
                break;
            case 'index':
                // Filter button
                $buttons[] = [
                    'class' =>  'fa fa-search',
                    'link'  =>  'javascript:void(0);',
                    'name'  =>  __('Search'),
                    'data-toggle'   =>  'modal',
                    'data-target'   =>  '#filter-modal',
                ];

                $buttons[] = [
                    'class' =>  'fa fa-plus',
                    'link'  =>  route($routePrefix.'create'),
                    'name'  =>  __('Add'),
                ];

                break;
        }
        return $buttons;
    }

    public static function generateRowDropDown($dropdowns): string
    {
        $html = "<div>
                  <ul class='nav'>";
        foreach ($dropdowns as $oneDropdown) {
            $html .= "<a
                    " . (array_key_exists('id', $oneDropdown) ? "id='{$oneDropdown['id']}'" : null) . "
                    " . (array_key_exists('url', $oneDropdown) ? "href='{$oneDropdown['url']}'" : null) . "
                    " . (array_key_exists('onclick', $oneDropdown) ? "onclick=\"{$oneDropdown['onclick']}\" href='javascript:void(0)'" : null) . "
                    >
                    <li class='nav-item'><i class='{$oneDropdown['class']}'></i></li>
                  </a>";
        }
        $html .= "</ul>
            </div>";
        return $html;
    }
}
