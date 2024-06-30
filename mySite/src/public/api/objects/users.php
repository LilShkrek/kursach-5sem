<?php

class Users {
    
    private $conn;
    private $table_name = "users";

    public $id;
    public $login;
    public $role;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    function get_all_users() {
        $users_get = "SELECT * FROM users";
        $users = [];
        if ($result = $this->conn->query($users_get)) {
            foreach ($result as $row) {
                $user = array(
                    "login" => $row['login'],
                    "role" => $row['role'],
                    "password" => $row['password']
                );
                array_push($users, $user);
            }
        }
        if ($users) {
            http_response_code(200);
        }
        return $users;
    }

    function get_user() {
        $user_get = "SELECT * from users
                     WHERE login = '$this->login'";
        $user = [];
        if ($result = $this->conn->query($user_get)) {
            foreach($result as $row) {
                $user = array(
                    "id" => $row['id'],
                    "login" => $row['login'],
                    "role" => $row['role'],
                    "password" => $row['password']
                );
            }
        } 
        return $user;
    }
    
    function get_password() {
        $user_get = "SELECT * from users WHERE login = '$this->login'";
        if ($result = $this->conn->query($user_get)) {
            foreach($result as $row) {
                $password = $row['password'];
            }
        } 
        return $password;
    }

    function create_user() {
        $user_create = "INSERT INTO users (login, role, password) VALUES ('$this->login',
            '$this->role', '$this->password');";

        if ($this->conn->query($user_create)) {
            return true;
        }
        return false;
    }

    function delete_user() {
        $user_delete = "DELETE FROM users WHERE login = '$this->login';";

        if ($this->conn->query($user_delete)) {
            return true;
        }
        
        return false;
    }

    function update_user() {
        $user_update = "UPDATE users SET password = '$this->password' WHERE login = '$this->login'";
        if ($this->conn->query($user_update)) {
            return true;
        }
        return false;
    }
}
?>