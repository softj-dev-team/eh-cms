<?php

namespace App\Traits;

trait ConnectMySql
{
    protected $mysqli;

    public function initMySql() {
        $this->mysqli = new \mysqli('115.77.189.247:33070', 'root', '01Brickmate23!@', 'eh');
    }

    protected function connectMySql() {
        $servername = "115.77.189.247:33070";
        $username = "root";
        $password = "01Brickmate23!@";
        $dbname = "eh";

        try {
            $conn = new \PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            return $conn;

        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
