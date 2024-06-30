<?php
class Database
{
    private $host = "localhost";
    private $db_name = "scheduleDB";
    private $username = "user";
    private $password = "password";
    public $conn;

    // Подключение к БД
    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new mysqli("mysql", "$this->username", "$this->password", "$this->db_name");
            mysqli_set_charset($this->conn, 'utf8mb4');
        } catch (mysqli_sql_exception $exception) {
            echo "Ошибка подключения: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
