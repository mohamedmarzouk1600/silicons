<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 10/6/2018 9:52 PM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

function GenerateMenu($arr)
{
    /**
     * Permission Check
     */

    if (isset($arr['permission'])) {
        if (auth('admin')->user() instanceof \MaxDev\Models\Admin) {
            if (!AdminCan($arr['permission'])) {
                return null;
            }
        }
    }

    $template = config('settings.template');
    switch ($template) {
        case 'Lorax':
            return generateLoraxMenu($arr);
        break;
        case 'Welly':
            return generateWellyMenu($arr);
        break;
        default:
            return generateStackMenu($arr);
    }
}

function generateStackMenu($arr): ?string
{
    $response = '<li class=" nav-item '.((isset($arr['sub']) && count($arr['sub'])) ? 'has_sub' : null).((isset($arr['class'])) ? ' '.$arr['class'] : null).'
    '.(isset($arr['url']) ? (($arr['url']==request()->fullUrl()) ? 'active' : null) : null).'">
                <a href="'.((isset($arr['url'])) ? $arr['url'] : 'javascript:void(0);').'"><i class="'.((isset($arr['icon'])) ? $arr['icon'] : null).'"></i>
                <span title="'.$arr['text'].'" data-i18n="" class="menu-title">'.$arr['text'].'</span></a>';
    if (isset($arr['sub'])) {
        $arr['sub'] = array_filter($arr['sub']);
    }
    if (isset($arr['sub']) && is_array($arr['sub'])) {
        $response .= "<ul class='menu-content'>\r\t";
        foreach ($arr['sub'] as $onesub) {
            $response .= "\t";
            $response .= GenerateMenu($onesub);
        }
        $response .= "\t</ul>\r";
    }
    $response .= '</li>';
    return $response;
}

function generateLoraxMenu($arr)
{
    $response = '<li class="'.((isset($arr['class'])) ? ' '.$arr['class'] : null).'
    '.(isset($arr['url']) ? (($arr['url']==request()->fullUrl()) ? 'active' : null) : null).'">
                <a href="'.((isset($arr['url'])) ? $arr['url'] : 'javascript:void(0);').'" class="'.((isset($arr['sub']) && count($arr['sub'])) ? 'menu-toggle' : null).'">
                <i class="'.((isset($arr['icon'])) ? $arr['icon'] : null).'"></i>
                <span title="'.$arr['text'].'">'.$arr['text'].'</span></a>';
    if (isset($arr['sub'])) {
        $arr['sub'] = array_filter($arr['sub']);
    }
    if (isset($arr['sub']) && is_array($arr['sub'])) {
        $response .= "<ul class='ml-menu'>\r\t";
        foreach ($arr['sub'] as $onesub) {
            $response .= "\t";
            $response .= generateLoraxMenu($onesub);
        }
        $response .= "\t</ul>\r";
    }
    $response .= '</li>';
    return $response;
}



function generateWellyMenu($arr): ?string
{
    $response = '<li class="'.(isset($arr['url']) ? (($arr['url']==request()->fullUrl()) ? 'active' : null) : null).'">
                <a href="'.((isset($arr['url'])) ? $arr['url'] : 'javascript:void(0);').'"><i class="'.((isset($arr['icon'])) ? $arr['icon'] : null).((isset($arr['sub']) && count($arr['sub'])) ? ' has-arrow ai-icon' : null).'"></i>
                <span title="'.$arr['text'].'" data-i18n="" class="menu-title">'.$arr['text'].'</span></a>';
    if (isset($arr['sub'])) {
        $arr['sub'] = array_filter($arr['sub']);
    }
    if (isset($arr['sub']) && is_array($arr['sub'])) {
        $response .= "<ul aria-expanded='false'>\r\t";
        foreach ($arr['sub'] as $onesub) {
            $response .= "\t";
            $response .= generateWellyMenu($onesub);
        }
        $response .= "\t</ul>\r";
    }
    $response .= '</li>';
    return $response;
}



function GenerateHorizMenu($arr, $sub=false)
{
    if ((is_array($arr)) && (!$sub)) {
        echo '<li data-menu="'.((isset($arr['sub']) && count($arr['sub'])) ? 'dropdown' : 'dropdown-submenu').'" class="'.((isset($arr['sub']) && count($arr['sub'])) ? ((!$sub) ? 'nav-item' : null).' dropdown' : null).' '.(($sub && isset($arr['sub'])) ? ' dropdown-submenu' : null).' '.((isset($arr['class'])) ? $arr['class'] : null).'">
        <a data-toggle="dropdown" class="dropdown-item '.((isset($arr['sub']) && count($arr['sub'])) ? 'dropdown-toggle '.(($sub) ? null : 'nav-link') : null).'" href="'.((isset($arr['href'])) ? $arr['href'] : '#').'">';
        if (isset($arr['sub']) && count($arr['sub']) && !$sub) {
            echo '<i class="'.((isset($arr['iclass'])) ? ' '.$arr['iclass'] : null).' "></i><span>';
        }
        echo $arr['text'];
        if (isset($arr['sub']) && count($arr['sub']) && !$sub) {
            echo '</span>';
        }
        echo '</a>';
        if (isset($arr['sub'])) {
            $arr['sub'] = array_filter($arr['sub']);
        }
        if (isset($arr['sub']) && is_array($arr['sub'])) {
            echo "<ul class='dropdown-menu'>\r\t";
            foreach ($arr['sub'] as $onesub) {
                echo "\t";
                GenerateHorizMenu($onesub, true);
            }
            echo "\t</ul>\r";
        }
        echo '</li>';
    } else {
        echo '<li class="nav-item">
                <a href="'.$arr['href'].'" class="nav-link">
                <i class="'.$arr['iclass'].'"></i>
                <span>'.$arr['text'].'</span></a>
              </li>';
    }
}
