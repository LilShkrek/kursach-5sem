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

if ($method == 'POST') {
    if ($_SESSION['authorized_user']) {
        echo json_encode (
            array (
                "message" => "Чтобы создать пользователя, необходимо быть НЕ авторизованным в системе!"
            ),
            JSON_UNESCAPED_UNICODE
        );
        return;
    }
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['login']) &&
        !empty($data['password'])
    ) {
        $users->login = $data['login'];
        $users->role = 0;
        $users->password = $data['password'];

        if (strlen($users->password) < 4) {
            http_response_code(400);
            echo json_encode(
                array (
                    "message" => "Длина пароля должна быть больше 3 символов. Попробуйте еще раз!"
                ),
                JSON_UNESCAPED_UNICODE
            );
            return;
        }

        if ($users->get_user()) {
            http_response_code(400);
            echo json_encode (
                array (
                    "message" => "Пользователь с такой логином уже существует. Попробуйте другой!"
                ),
                JSON_UNESCAPED_UNICODE
            );
            return;
        }
        
        if ($users->create_user()) {
            http_response_code(200);
            echo json_encode(array("message" => "Пользователь создан!"), JSON_UNESCAPED_UNICODE);
        }

        else {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно создать пользователя!"), JSON_UNESCAPED_UNICODE);
        }
    }

    else {
        http_response_code(400);
        echo json_encode(array("message" => "Невозможно создать пользователя. Данные неполные!"), JSON_UNESCAPED_UNICODE);
    }
}
