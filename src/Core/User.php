<?php

namespace Tm\Auth\Core;

class User {
    private $db, $data, $session_name, $isLoggedIn, $cookieName;

    public function __construct($user = null) {
        $this->db = Database::getInstance();
        $this->session_name = Config::get('session.user_session');
        $this->cookieName = Config::get('cookie.cookie_name');

        if(!$user) { 
            if(Session::exists($this->session_name)) {
                    if (is_array(Session::get($this->session_name)) && (Session::get($this->session_name)['role'] === 1)) {
                        $this->data = json_decode(json_encode(Session::get($this->session_name)));
                        $this->isLoggedIn = true;
                    } else {
                        $user = Session::get($this->session_name); //id
                        if($this->find($user)) {
                             $this->isLoggedIn = true;
                        } 
                    }
            }
        } else {
            $this->find($user);
        }
        
    }

    public function create($fields = []) {
        $this->db->insert($this->session_name , $fields);
    }

    public function login($email = null, $password = null, $remember = false) {
        //if((Session::exists($this->session_name)) && (Session::get($this->session_name)['role'] === 1)) {
        if(is_array(Session::get($this->session_name)) && (Session::get($this->session_name)['role'] === 1)) {
            return true;
        } else {
            if(!$email && !$password && $this->exists()) {
                Session::put($this->session_name, $this->data()->id);
            } else {
                $user = $this->find($email);
                if($user) {
                    if(password_verify($password, $this->data()->password)) {
                        Session::put($this->session_name , $this->data()->id);
                        // Если стоит чек Запомнить проверяем хек в куках
                        if($remember) {
                            $hash = hash('sha256', uniqid());
                            $hashCheck = $this->db->get(Config::get('cookie.cookie_table'), ['user_id', '=', $this->data->id]);
                            if(!$hashCheck->count()) {
                                $this->db->insert(Config::get('cookie.cookie_table'), [
                                    'user_id' => $this->data()->id,
                                    'hash' => $hash
                                ]);
                            } else {
                                $hash = $hashCheck->first()->hash;
                            }
                            Cookie::put($this->cookieName, $hash, Config::get('cookie.cookie_expiry'));
                        }
                        return true;
                    }
                }
            }
        }    
        return false;

    }

    public function find($field = null) {
        if(is_numeric($field)) {
            $this->data = $this->db->get($this->session_name, ['id', '=', $field])->first();
        } else {
            $this->data = $this->db->get($this->session_name, ['email', '=', $field])->first();
        }
        if($this->data) {
            return true;
        }
        return false;
    }

    public function data() {
        return $this->data;
    }

    public function isLoggenIn() {
        return $this->isLoggedIn;
    }

    public function logout() {
        Session::delete($this->session_name);
        Session::delete('token');
        $this->db->delete('user_sessions', ['user_id', '=', $this->data()->id]);
        Cookie::delete($this->cookieName);
    }

    public function exists() {
      return (!empty($this->data())) ? true : false;
    }

    public function update($fields = [], $id = null) {
        if(!$id && $this->isLoggenIn()) {
            $id = $this->data()->id;
        }
        $this->db->update($this->session_name, $id, $fields);
    }

}