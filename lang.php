<?php
function userLanguage($baseUrl, $lang)
{
    $file = $_SERVER['DOCUMENT_ROOT'].$baseUrl.'/locales/'. $lang . '.json';
    if (!file_exists($file)) {
        $file = $_SERVER['DOCUMENT_ROOT'].$baseUrl. '/locales/'.Flight::get('ini')['mainLanguage'].'.json';
    }
    return json_decode(file_get_contents($file), true);
}

function __($string)
{
    $lang = getLang();
    if (!Flight::has('i18n')) {
        Flight::set('i18n', userLanguage(Flight::request()->base, $lang));
    }
    $translation = Flight::get('i18n');

    if (isset($translation[$string])) {
        return $translation[$string];
    } else {
        return $string;
    }
}

function setLang($lang)
{
    setcookie("extractLang", $lang, time()+31536000, '/');
    Flight::set('lang', $lang);
}

function getLang()
{
    if (!isset($_COOKIE["extractLang"])) {
        setLang(Flight::get('ini')['mainLanguage']);
    }
    if (Flight::has('lang')) {
        return Flight::get('lang');
    }
    return $_COOKIE["extractLang"];
}
