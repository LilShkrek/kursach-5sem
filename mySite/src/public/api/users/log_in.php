<?php

// HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Необходимые импорты
include_once "../config/database.php";
include_once "../objects/users.php";
include_once "../users/authorized_user.php";

// Подключение к БД
$database = new Database();
$db = $database->getConnection();
$users = new users($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method = 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!empty($data['login']) && !empty($data['password'])) {
        $users->login = $data['login'];
        $users->password = $data['password'];
        $users->role = $users->get_user()['role'];

        if (!$users->get_user()) {
            http_response_code(400);
            echo json_encode (
                array (
                    "message" => "Такого пользователя не существует!"
                ),
                JSON_UNESCAPED_UNICODE
            );
            return;
        }

        if ($users->get_password() != $users->password) {
            http_response_code(400);
            echo json_encode (
                array (
                    "message" => "Неверно введенный пароль!"
                ),
                JSON_UNESCAPED_UNICODE
            );
            return;
        }

        if ($_SESSION['authorized_user']) {
            http_response_code(400);
            echo json_encode (
                array (
                    "message" => "Вы уже вошли в аккаунт"
                ),
                JSON_UNESCAPED_UNICODE
            );
            return;
        }

        $_SESSION['authorized_user'] = $users->login;
        $_SESSION['authorized_role'] = $users->role;
        
        echo json_encode (
            array (
                "message" => "Вы успешно вошли в аккаунт!"
            ),
            JSON_UNESCAPED_UNICODE
        );
    } else {
        http_response_code(400);
            echo json_encode (
                array (
                    "message" => "Такого пользователя не существует"
                ),
                JSON_UNESCAPED_UNICODE
            );
            return;
    }
}