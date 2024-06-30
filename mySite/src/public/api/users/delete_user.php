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

if ($method == 'DELETE') {
    if (!$_SESSION['authorized_user']) {
        http_response_code(401);
        echo json_encode(
            array (
                "message" => "Чтобы удалить аккаунт, необходимо авторизоваться в системе!"
            ),
            JSON_UNESCAPED_UNICODE
        );
    } else {
        $users->login = $_SESSION['authorized_user'];
        
        if ($users->delete_user()) {
            http_response_code(201);
            $_SESSION['authorized_user'] = "";
            $_SESSION['authorized_role'] = "";
            echo json_encode (
                array (
                    "message" => "Ваш аккаунт успешно удален!"
                ),
                JSON_UNESCAPED_UNICODE
            );
        } else {
            http_response_code(503);
            echo json_encode(
                array (
                    "message" => "Ошибка удаления пользователя"
                ),
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}