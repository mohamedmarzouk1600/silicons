<?php

namespace MaxDev\Services\AdminTemplate;

class StackTemplate implements TemplateInterface
{
    public static function generateButtons($routePrefix, $row, $type): array
    {
        $buttons = [];
        switch ($type) {
            case 'create':
                break;
            case 'show':
                $buttons[] = [
                    'class' =>  'ft-edit',
                    'link'  =>  route($routePrefix.'edit', $row->id)
                ];
                break;
            case 'index':
                // Filter button
                $buttons[] = [
                    'class' =>  'ft-search',
                    'link'  =>  'javascript:void(0);',
                    'data-toggle'   =>  'modal',
                    'data-target'   =>  '#filter-modal',
                ];
                $buttons[] = [
                    'class' =>  'ft-plus',
                    'link'  =>  route($routePrefix.'create')
                ];
                break;

            case 'search':
                // Filter button
                $buttons[] = [
                    'class' =>  'ft-search',
                    'link'  =>  'javascript:void(0);',
                    'data-toggle'   =>  'modal',
                    'data-target'   =>  '#filter-modal',
                ];
                break;
        }
        return $buttons;
    }

    public static function generateRowDropDown($dropdowns): string
    {
        $html = "<div class='dropdown'>
                      <button class='btn btn-secondary dropdown-toggle' type='button' data-toggle='dropdown'><i class='ft-pocket icon-left'></i>
                      <span class='caret'></span></button>
                      <ul class='dropdown-menu'>";
        foreach ($dropdowns as $oneDropdown) {
            if (auth('admin')->check() && adminCan($oneDropdown['route'])) {
                $html .= "<a
                    " . (array_key_exists('id', $oneDropdown) ? "id='{$oneDropdown['id']}'" : null) . "
                    " . (array_key_exists('url', $oneDropdown) ? "href='{$oneDropdown['url']}'" : null) . "
                    " . (array_key_exists('target', $oneDropdown) ? "target='{$oneDropdown['target']}'" : null) . "
                    " . (array_key_exists('onclick', $oneDropdown) ? "onclick=\"{$oneDropdown['onclick']}\" href='javascript:void(0)'" : null) . "
                    >
                    <li class='dropdown-item'><i class='{$oneDropdown['class']}'></i> {$oneDropdown['text']}</li>
                  </a>";
            }
        }
        $html .= "</ul>
            </div>";
        return $html;
    }
}
