<?php
namespace App\Helpers;


class HelperString
{
    public static function arrayToStringWithBreakLines($array)
    {
        $str = '';
        foreach ($array as $n => $v) {
            $str .= $n.': '.$v.PHP_EOL;
        }
        return $str;
    }
}