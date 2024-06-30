<?php

// HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Необходимые импорты
include_once "../config/database.php";
include_once "../objects/users.php";
include_once "../objects/schedule.php";
include_once "../users/authorized_user.php";

// Подключение к БД
$database = new Database();
$db = $database->getConnection();

// Инициализация объекта
$schedule = new Schedule($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    if (!$_SESSION['authorized_user']) {
        echo json_encode(
            array (
                "message" => "Чтобы добавлять записи, необходимо авторизоваться в системе!"
            ),
            JSON_UNESCAPED_UNICODE
        );
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['name']) && !empty($data['description']) && !empty($data['deadline'])) {
        $schedule->name = $data['name'];
        $schedule->description = $data['description'];
        $schedule->deadline = $data['deadline'];
        $schedule->owner = $_SESSION['authorized_user'];

        if($schedule->create_schedule()) {
            http_response_code(200);
            echo json_encode(
                array (
                    "message" => "Запись успешно сделана!"
                ),
                JSON_UNESCAPED_UNICODE
            );
        } else {
            http_response_code(400);
            echo json_encode(
                array (
                    "message" => "Не удалось сделать запись!"
                ),
                JSON_UNESCAPED_UNICODE
            );
            return; 
        }
    } else {
        http_response_code(400);
        echo json_encode(
            array (
                "message" => "Данные неполные!"
            ),
            JSON_UNESCAPED_UNICODE
        );
        return;
    }
}