<?php

class Schedule {

    private $conn;
    private $table_name = "schedule";

    public $id;
    public $name;
    public $description;
    public $owner;
    public $deadline;

    public function __construct($db) {
        $this->conn = $db;
    }
    
    function get_schedule_by_owner() {
        if ($_SESSION['authorized_role'] == 0) {
            $schedule_get = "SELECT * from schedule WHERE owner = '$this->owner'";
        }else {
            $schedule_get = "SELECT * from schedule";
        }
        $schedule = [];
        if ($result = $this->conn->query($schedule_get)) {
            foreach ($result as $row) {
                $record = array (
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "description" => $row['description'],
                    "owner" => $row['owner'],
                    "deadline" => $row['deadline']
                );
                array_push($schedule, $record);
            }
        }
        if ($schedule) {
            http_response_code(200);
        }
        return $schedule;
    }

    function create_schedule() {
        $schedule_create = "INSERT INTO schedule (name, description, owner, deadline) VALUES
            ('$this->name', '$this->description', '$this->owner', '$this->deadline');";
        if ($this->conn->query($schedule_create)) {
            return true;
        }
        return false;
    }

    function delete_schedule() {
        $schedule_delete = "DELETE from schedule WHERE id = '$this->id';";

        if ($this->conn->query($schedule_delete)) {
            return true;
        }

        return false;
    }

    function update_schedule() {
        $schedule_update = "UPDATE schedule set name = '$this->name', description = '$this->description', deadline = '$this->deadline' WHERE id = '$this->id'";
        if ($this->conn->query($schedule_update)) {
            return true;
        }
        return false;
    }

    function get_schedule_by_id() {
        $schedule_get = "SELECT * from schedule
                         WHERE id = '$this->id'";
        $schedule = [];
        if ($result = $this->conn->query($schedule_get)) {
            foreach($result as $row) {
                $schedule = array(
                "id" => $row['id'],
                "name" => $row['name'],
                "description" => $row['description'],
                "owner" => $row['owner'],
                "deadline" => $row['deadline']
                );
            }
        } 
        return $schedule;
    }
}