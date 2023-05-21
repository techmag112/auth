<?php

namespace Tm\Auth\Core;

class Redirect {
    public static function to($location = '/') {

        header('Location: ' . $location);
        
    }
}