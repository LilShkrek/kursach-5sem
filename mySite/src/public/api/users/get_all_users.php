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

// Инициализация объекта
$users = new Users($db);
$responce_users = $users->get_all_users();
if (!$responce_users) {
    http_response_code(404);
    echo json_encode(
        array (
            "message" => "Не удалось выполнить запрос!"
        ),
        JSON_UNESCAPED_UNICODE
    );
    } else {
        http_response_code(200);
        echo json_encode($responce_users);
    }
