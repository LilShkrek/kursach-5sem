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

if ($method = 'PUT') {

    if (!$_SESSION['authorized_user']) {
        http_response_code(400);
        echo json_encode (
            array (
                "message" => "Чтобы выйти из аккаунта, необходимо быть авторизованным в системе!"
            ),
            JSON_UNESCAPED_UNICODE
        );
        return;
    }
    session_reset();
    $_SESSION['authorized_user'] = "";
    $_SESSION['authorized_role'] = "";
    echo json_encode(
        array (
            "message" => "Вы успешно вышли из аккаунта!"
        ),
        JSON_UNESCAPED_UNICODE
    );

}