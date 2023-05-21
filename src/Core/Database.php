<?php

namespace Tm\Auth\Core;
use PDO;

class Database {

    private static $instance = null;
    private $pdo, $query, $result, $count, $error = false;

    private function __construct() {
        try {
            $this->pdo = new PDO('mysql:host=' . Config::get('mysql.host') . '; dbname=' . Config::get('mysql.database') . '; ', Config::get('mysql.username'), Config::get('mysql.password'));
        } catch (PDOException $e) {
            echo $e->getMessage(); die;
        }
    }

    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function query($sql, $params = []) {
        $this->error = false;
        $this->query = $this->pdo->prepare($sql);

        if(count($params)) {
            $i = 1;
            foreach($params as $param) {
                $this->query->bindValue($i, $param);
                $i++;
            }
        }
        if(!$this->query->execute()) {
            $this->error = true;
        } else {
            $this->result = $this->query->fetchAll(PDO::FETCH_OBJ);
            $this->count = $this->query->rowCount();
        }
        return $this;
    }

    public function error() {
        return $this->error;
    }

    public function results() {
        return $this->result;
    }

    public function count() {
        return $this->count;
    }

    public function get($table, $where = []) {
        return $this->action('SELECT * ', $table, $where);
    }
    
    public function delete($table, $where = []) {
        return $this->action('DELETE ', $table, $where);
    }

    public function action($action, $table, $where) {
        if(count($where) === 3) {
            [$field, $operator, $value] = $where;
            $operators = ['=', '>', '<', '>=', '<='];
            if(in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if(!$this->query($sql, [$value])->error()) {
                    return $this;
                }
            }
        } else {
            $sql = "{$action} FROM {$table}";
                if(!$this->query($sql)->error()) {
                    return $this;
                }
        }
        return false;
    }

    public function insert($table, $fields = []) {
        $values ='';
        foreach($fields as $field) {
            $values .= '?,';
        }        
        $values = rtrim($values);
        $values = rtrim($values, ',');

        $sql = "INSERT INTO {$table} (`" . implode("`,`", array_keys($fields)) . "`) VALUES ({$values})";

        if(!$this->query($sql, $fields)->error()) {
            return $this;
        }

        return false;

    }

    public function update($table, $id, $fields = []) {
        $values ='';
        foreach($fields as $key => $field) {
            $values .= $key . ' = ?, ';
        }        
        $values = rtrim($values);
        $values = rtrim($values, ",");

        $sql = "UPDATE {$table} SET {$values} WHERE id= ?";

        $fields += ['id' => $id];

        if(!$this->query($sql, $fields)->error()) {
            return $this;
        }

        return false;

    }

    public function first() {
        return $this->results()[0] ?? null;
    }

}
