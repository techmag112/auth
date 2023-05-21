<?php

namespace Tm\Auth\App;

use \Tm\Auth\Core\Cookie;
use \Tm\Auth\Core\Router;

session_start();

$GLOBALS['config']  =   [
    'mysql' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'project',
    ],            
    'session' => [
        'token_name' => 'token',
        'user_session' => 'users'
    ],
    'cookie' => [
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800,
        'cookie_table' => 'user_sessions',
    ],
    'vk' => [
        'client_id' => '51651544',
        'client_secret' => 'Rv4mgN90YCiPCNZcjcZf',
        'redirect_uri' => 'http://task27/oauth',
    ]
];

Cookie::autologin();
$router = new Router();
$router->run(); 