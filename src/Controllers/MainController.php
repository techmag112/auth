<?php

namespace Tm\Auth\Controllers;

use \Tm\Auth\Core\User;
use \Tm\Auth\Core\Database;
use \League\Plates\Engine;

class MainController {

    private $user, $db, $templates;

    function __construct() {
        $this->user = new User();
        $this->db = Database::getInstance();
        $this->templates = new Engine('src/Templates');
    }

    public function mainAction($method) {
        if($this->user->isLoggenIn()) {
            echo $this->templates->render('main', [
                'username' => $this->user->data()->username, 
                'role' => $this->user->data()->role, 
                //'message' => $message, 
                //'type' => $type,
            ]);
        }
        else {
            echo $this->templates->render('main');
        }
    }
}