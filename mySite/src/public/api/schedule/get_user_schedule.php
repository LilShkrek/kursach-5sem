<?php

// HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Необходимые импорты
include_once "/web/public/api/config/database.php";
include_once "/web/public/api/objects/users.php";
include_once "/web/public/api/objects/schedule.php";
include_once "/web/public/api/users/authorized_user.php";

// Подключение к БД
$database = new Database();
$db = $database->getConnection();

// Инициализация объекта
$schedule = new Schedule($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {

    if (!$_SESSION['authorized_user']) {
        http_response_code(400);
        echo json_encode (
            array (
                "message" => "Чтобы просматривать записи, необходимо авторизоваться!"
            ),
            JSON_UNESCAPED_UNICODE
        );
        return;
    }
    else {
        $schedule->owner = $_SESSION['authorized_user'];
        echo json_encode($schedule->get_schedule_by_owner());
    }
}