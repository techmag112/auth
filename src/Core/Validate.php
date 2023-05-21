<?php 

namespace Tm\Auth\Core;

class Validate {
    private $passed = false, $errors = [], $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function check($source, $items = []) {
        foreach($items as $item => $rules) {
            foreach($rules as $rule => $rule_value) {
                
                $value = $source[$item];

                if($rule == 'required' && empty($value)) {
                    $this->addError("{$item} обязателен!");
                } else if(!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if(strlen($value) < $rule_value) {
                                $this->addError("{$item} должен быть минимум {$rule_value} символов.");
                            }
                        break;
                        case 'max':
                            if(strlen($value) > $rule_value) {
                                $this->addError("{$item} должен быть максимум {$rule_value} символов.");
                            }
                        break;
                        case 'matches':
                            if($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} должен совпадать с {$item}");
                            }
                        break;
                        case 'unique':
                            $check = $this->db->get($rule_value, [$item, '=', $value]);
                            if($check->count()) {
                                $this->addError("{$item} уже существует!");
                            }
                        break;
                        case 'email':
                            if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError("{$item} не похож на email");
                            }
                        break;
                    }
                }

            }
        }

        if(empty($this->errors)) {
            $this->passed = true;
        }

        return $this;
    }

    public function addError($error) {
        $this->errors[] = $error;
    }

    public function error() {
        return $this->errors;
    }

    public function passed() {
        return $this->passed;
    }
}