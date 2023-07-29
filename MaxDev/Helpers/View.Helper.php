<?php


function ExtractLocaleUri()
{
    $uri = explode('/', $_SERVER['REQUEST_URI']);
    if (in_array($uri[1], ['ar', 'en'])) {
        $GLOBALS['Local'] = $uri[1];
        array_shift($uri);
        array_shift($uri);
    }
    $uri = trim(implode('/', $uri), '/');
    return '/' . $uri;
}

function formError($error, $fieldName, $checkHasError = false)
{
    if ($checkHasError) {
        if ($error->has($fieldName)) {
            return ' has-danger';
        } else {
            return null;
        }
    }
    if ($error->has($fieldName)) {
        $return = '<p class="text-xs-left"><small class="danger text-muted">';
        foreach ($error->get($fieldName) as $errorMsg) {
            if (is_array($errorMsg)) {
                $return .= implode(',', $errorMsg) . '<br />';
            } else {
                $return .= $errorMsg . '<br />';
            }
        }
        $return .= '</small></p>';
        return $return;
    } else {
        return null;
    }
}


function ChangeLanguageTo($lang)
{
    if (in_array($lang, config('app.locales'))) {
        return asset($lang . request()->getRequestUri());
    }
    return asset(request()->getRequestUri());
}

if (!function_exists('langLabel')) {
    function langLabel(string $language)
    {
        return match ($language) {
            \MaxDev\Enums\Language::Arabic => ' (عربي)',
            default => ' (Englishِ) ',
        };
    }
}
