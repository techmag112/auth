<?php

namespace Tm\Auth\Core;

class Cookie {

    public static function exists($name) {
        return (isset($_COOKIE[$name])) ? true : false;
    }

    public static function get($name) {
        return $_COOKIE[$name];
    }

    public static function put($name, $value, $expiry) {
        if(setcookie($name, $value, time() + $expiry, '/')) {
            return true;
        }
        return false;
    }

    public static function delete($name) {
        self::put($name, '', time() - 1);
    }

    public static function autologin() {
        if(self::exists(Config::get('cookie.cookie_name')) && !Session::exists(Config::get('session.user_session'))) {
            $hash = self::get(Config::get('cookie.cookie_name'));
            $hashCheck = Database::getInstance()->get(Config::get('cookie.cookie_table'), ['hash', '=', $hash]);
        
            if($hashCheck->count()) {
                $user = new User($hashCheck->first()->user_id);
                $user->login();
            }
        }
    }

}