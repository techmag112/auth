<?php

namespace Tm\Auth\Controllers;

use \Tm\Auth\Core\User;
use \Tm\Auth\Core\Database;
use \League\Plates\Engine;
use \Tm\Auth\Core\Validate;
use \Tm\Auth\Core\Input;
use \Tm\Auth\Core\Config;
use \Tm\Auth\Core\Token;
use \Tm\Auth\Core\Redirect;
use \Tm\Auth\Core\Session;
use \Tm\Auth\Core\VK;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class LoginController {

    private $user, $validate, $templates, $message, $type, $session_name, $vk, $log;

    function __construct() {
        $this->user = new User();
        $this->db = Database::getInstance();
        $this->templates = new Engine('src/Templates');
        $this->validate = new Validate();
        $this->vk = new VK();
        $this->session_name = Config::get('session.user_session');
        $this->log = new Logger('mylogger');
        $this->log->pushHandler(new StreamHandler('logs/log.log', Logger::WARNING));
    }

    public function logoutAction() {
        $this->user->logout();
        Redirect::to('/');
    }

    public function updateAction($method) {
        if(Input::exists()) {
            if(Token::check(Input::get('token'))) {
                $validation = $this->validate->check($_POST, [
                    'username' => [
                        'required' => true,
                        'min' => 2,
                        'max' => 15,
                        'unique' => 'users'
                    ],
                ]);
        
                if($validation->passed()) {
                    $this->user->update(['username' => Input::get('username')]);
                    $this->message = 'Успешно обновлено!'; 
                    $this->type = "success";
                    Redirect::to('/');
                } else {
                    $errors = '';
                    foreach($validation->error() as $error) {
                        $errors .= $error . '<br>';
                    }
                    $this->message = $errors; 
                    $this->type = "error";
                    $this->log->warning($errors);
                }
            }
        } 
        echo $this->templates->render('update', [
            'username' => $this->user->data()->username, 
            'message' => $this->message, 
            'type' => $this->type
        ]);  
    }

    public function changepassAction($method) {
        if(Input::exists()) {
            if(Token::check(Input::get('token'))) {
                $validation = $this->validate->check($_POST, [
                    'new_pass' => [
                        'required' => true,
                        'min' => 3,
                    ],
                    'new_pass_again' => [
                        'required' => true,
                        'matches' => 'new_pass',
                    ],
                ]);
        
                if($validation->passed()) {
        
                    if(password_verify(Input::get('current_pass'), $this->user->data()->password)) {
                        $this->user->update(['password' => password_hash(Input::get('new_pass'), PASSWORD_DEFAULT)]);
                        $this->message = 'Пароль успешно изменен.'; 
                        $this->type = "success";
                        Redirect::to('/');
                    } else {  
                        $message = 'Текущий пароль неверен'; 
                        $type = "error";
                        $this->log->warning($message);
                    }
                                   
                } else {
                    $errors = '';
                    foreach($validation->error() as $error) {
                        $errors .= $error . '<br>';
                    }
                    $this->message = $errors; 
                    $this->type = "error";
                    $this->log->warning($errors);
                }
            }
        }   
        echo $this->templates->render('changepass', [
            'username' => $this->user->data()->username, 
            'message' => $this->message, 
            'type' => $this->type
        ]);  
    }

    public function regAction($method = 'post') {
        if(Input::exists()) {
            if(Token::check(Input::get('token'))) {
                $validation = $this->validate->check($_POST, [
                    'username' => [
                        'required' => true,
                        'min' => 2,
                        'max' => 15,
                        'unique' => 'users'
                    ],
                    'email' => [
                        'required' => true,
                        'email' => true,
                        'unique' => 'users'
                    ],
                    'password' => [
                        'required' => true,
                        'min' => 3,
                    ],
                    'password_again' => [
                        'required' => true,
                        'matches' => 'password',
                    ],
                ]);
        
                if($validation->passed()) {
                    $this->user->create([
                        'username' => Input::get('username'),
                        'password' => password_hash(Input::get('password'), PASSWORD_DEFAULT),
                        'email' => Input::get('email'),
                    ]);
                    $this->message = "Регистрация успешно выполнена"; 
                    $this->type = "success";
        
                } else {
                    $errors = '';
                    foreach ($this->validate->error() as $error) {
                        $errors .= $error . '<br>';
                    }
                    $this->message = $errors; 
                    $this->type = "error";
                    $this->log->warning($errors);
                }
            }
        } 
        echo $this->templates->render('register', [
            'message' => $this->message, 
            'type' => $this->type
        ]);  
    }

    public function mainAction($method) {
        $this->message = null; 
        $this->type = null;
        if(Input::exists()) {
            if(Token::check(Input::get('token'))) {       
                $this->validate->check($_POST, [
                    'email' => ['required' => true, 'email' => true],
                    'password' => ['required' => true],
                ]);
        
                if($this->validate->passed()) {
                    $remember = (Input::get('remember') === 'on') ? true : false;
        
                    $login = $this->user->login(Input::get('email'), Input::get('password'), $remember);
                    if($login) {
                        Redirect::to('/');
                    } else {
                        $this->message = "Неверный логин или пароль!";
                        $this->type = "error";
                        $this->log->warning($this->message);
                    }
                } else {
                    $errors = '';
                    foreach ($this->validate->error() as $error) {
                        $errors .= $error . '<br>';
                    }
                    $this->message = $errors; 
                    $this->type = "error";
                    $this->log->warning($errors);
                }
            }
        }   

        $params = array(
            'client_id'     => Config::get('vk.client_id'),
            'redirect_uri'  => Config::get('vk.redirect_uri'),
            'response_type' => 'code',
	        'v' => '5.131', 
	        'scope'         => 'photos,offline',
        );
       
        echo $this->templates->render('login', [
            'message' => $this->message, 
            'type' => $this->type,
            'params' => $params,
        ]);  
    }

    public function VKAction($method) {
        $this->message = null; 
        $this->type = null;
        if(Input::exists('get')) {
                if (isset($_GET['code'])) {
                    // Сформировать токен подключения
                    if (!$token = $this->vk->getToken($_GET['code'])) {
                        $this->message = "Ошибка авторизации с ВК: ошибка авторизации приложения"; 
                        $this->type = "error";
                        $this->log->error($this->message);
                    } else {
                   
                            $userInfo = $this->vk->getInfo($token);
            
                            if(!empty($userInfo) && (isset($userInfo['response'][0]['id']))) {    
                                    $userInfo = $userInfo['response'][0];
                                    Session::put($this->session_name , array(
                                        'id' => $userInfo['id'],
                                        'email' => '',
                                        'username' => $userInfo['first_name'] . ' ' . $userInfo['last_name'],
                                        'role' => 1,
                                    ));
                                    Redirect::to('/');
                            } else {  
                                $this->message = "Ошибка авторизации с ВК: неверный токен"; 
                                $this->type = "error";
                                $this->log->error($this->message);
                            }
                    }
                }
                
                echo $this->templates->render('login', [
                    'message' => $this->message, 
                    'type' => $this->type,
                    'params' => array(
                            'client_id'     => Config::get('vk.client_id'),
                            'redirect_uri'  => Config::get('vk.redirect_uri'),
                            'response_type' => 'code',
                            'v'             => '5.131',                                
                            'scope'         => 'photos,offline',)
                    ]);  
                
        }
    }
}