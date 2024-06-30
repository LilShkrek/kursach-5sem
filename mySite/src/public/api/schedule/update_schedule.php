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

if ($method == 'PUT') {
    if (!$_SESSION['authorized_user']) {
        http_response_code(401);
        echo json_encode(
            array (
                "message" => "Чтобы изменить запись, необходимо авторизоваться в системе!"
            ),
            JSON_UNESCAPED_UNICODE
        );
        return;
    }
    if(isset($_GET['id'])) {
        $id = $_GET['id'];      ////////////////////////////////////////////////////////
        $schedule->id = $id;
        if ($schedule->get_schedule_by_id()['owner'] == $_SESSION['authorized_user'] || $_SESSION['authorized_role'] == 1) {
            $postData = file_get_contents('php://input');
            $data = json_decode($postData, true);

            if (!empty($data['name']) &&
                !empty($data['description']) &&
                !empty($data['deadline'])
            ) {
                $schedule->name = $data['name'];
                $schedule->description = $data['description'];
                $schedule->deadline = $data['deadline'];
                
                if($schedule->update_schedule()) {
                    http_response_code(200);
                    echo json_encode(
                        array (
                            "message" => "Запись успешно обновлена!"
                        ),
                        JSON_UNESCAPED_UNICODE
                    );
                } else {
                    http_response_code(400);
                    echo json_encode(
                        array (
                            "message" => "Не удалось обновить запись!"
                        ),
                        JSON_UNESCAPED_UNICODE
                    );
                    return; 
                }

            }else {
                http_response_code(400);
                echo json_encode (
                    array (
                        "message" => "Данные неполные!"
                    ),
                    JSON_UNESCAPED_UNICODE
                );
            }
            
        } else {
            http_response_code(400);
                echo json_encode(
                    array (
                        "message" => "Эта запись не может быть изменена!"
                    ),
                    JSON_UNESCAPED_UNICODE
                );
            return;
        }

    }

}