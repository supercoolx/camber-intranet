<?php

class Site
{

    public static function isDevServer()
    {
        if (strpos($_SERVER['HTTP_HOST'], 'treng.net') !== false) {
            return true;
        } else {
            return false;
        }
    }

    public static function isDevIp()
    {

        $ip = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
        if (defined("ALLOWED_IPS_FOR_DEBUG"))
            if (in_array($ip, ALLOWED_IPS_FOR_DEBUG)) {
                return true;
            }
        return false;
    }

}
