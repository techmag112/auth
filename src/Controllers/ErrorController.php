<?php

namespace Tm\Auth\Controllers;

use \Tm\Auth\Core\User;
use \League\Plates\Engine;

class ErrorController {

    private $user, $templates;

    function __construct() {
        $this->user = new User();
        $this->templates = new Engine('src/Templates');
     }

    public function mainAction($method) {
        if($this->user->isLoggenIn()) {
            echo $this->templates->render('404', ['username' => $this->user->data()->username]);  
        } else {
            echo $this->templates->render('404');   
        }  
    }
}