<?php

namespace Tm\Auth\Core;

class Session {
 
    public static function put($name, $value) {
        return $_SESSION[$name] = $value;
    }

    public static function exists($name) {
        return (isset($_SESSION[$name])) ? true : false;
    }

    public static function delete($name) {
        if(self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    public static function get($name) {
        return $_SESSION[$name];
    }

    public static function setFlash($string, $style = 'success') {
        $string = '<div class="alert alert-' . $style .  '" role="alert">' . $string . '</div>';
        self::put('message', $string);
    }

    public static function showFlash() {
        $html = self::exists('message') ? self::get('message') : null;
        self::delete('message');
        return $html;
    }

}