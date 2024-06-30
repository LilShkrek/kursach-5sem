<?php

// HTTP заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Необходимые импорты
include_once "../config/database.php";
include_once "../objects/users.php";
include_once "../users/authorized_user.php";
include_once "../objects/schedule.php";

// Подключение к БД
$database = new Database();
$db = $database->getConnection();

// Инициализация объекта
$schedule = new Schedule($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'DELETE') {
    if (!$_SESSION['authorized_user']) {
        echo json_encode (
            array (
                "message" => "Чтобы удалить запись, необходимо авторизоваться!"
            ),
            JSON_UNESCAPED_UNICODE
        );
        return;
    }
    if(isset($_GET['id'])) {
        $id = $_GET['id'];      ////////////////////////////////////////////////////////
        $schedule->id = $id;
        if ($schedule->get_schedule_by_id()['owner'] == $_SESSION['authorized_user'] || $_SESSION['authorized_role'] == 1) {
            if($schedule->delete_schedule()) {
                echo json_encode(
                    array (
                        "message" => "Запись успешно удалена!"
                    ),
                    JSON_UNESCAPED_UNICODE
                );  
            } else {
                http_response_code(400);
                echo json_encode(
                    array (
                        "message" => "Не удалось удалить запись!"
                    ),
                    JSON_UNESCAPED_UNICODE
                );
                return;
            }     
        } else {
            http_response_code(400);
                echo json_encode(
                    array (
                        "message" => "Эта запись не может быть удалена!"
                    ),
                    JSON_UNESCAPED_UNICODE
                );
            return;
        }
    }
}