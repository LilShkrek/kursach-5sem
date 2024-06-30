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

if ($method == 'PUT') {
    if (!$_SESSION['authorized_user']) {
        http_response_code(401);
        echo json_encode(
            array (
                "message" => "Чтобы изменить пользователя, необходимо авторизоваться в системе!"
            ),
            JSON_UNESCAPED_UNICODE
        );
        return;
    }
    $postData = file_get_contents('php://input');
    $data = json_decode($postData, true);

    if (!empty($data['password'])
    ) {
        $users->password = $data['password'];
        $users->login = $_SESSION['authorized_user'];
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
        
        if (!$users->get_user()) {
            http_response_code(404);
            echo json_encode (
                array (
                    "message" => "Пользователь не найден!"
                ),
                JSON_UNESCAPED_UNICODE
            );
            return;
        }

        if ($users->update_user()) {
            http_response_code(200);
            echo json_encode(
                array(
                    "message" => "Пользователь успешно обновлен!"
                ),
                JSON_UNESCAPED_UNICODE
            );
        } else {
            http_response_code(400);
            echo json_encode(
                array (
                    "message" => "Не удалось обновить пользователя!"
                ),
                JSON_UNESCAPED_UNICODE
            );
        }
    } else {
        http_response_code(400);
        echo json_encode (
            array (
                "message" => "Данные неполные!"
            ),
            JSON_UNESCAPED_UNICODE
        );
    }
}