<?php

namespace App\Traits;

trait ConnectSql
{
    protected function connectSqlServer($dbName) {
        $serverName = "115.77.189.247";
        $uid = "sa";
        $pwd = "L7G4khHjnHSzEEsm";
        $databaseName = $dbName;

        $connectionInfo = [
            "UID" => $uid,
            "PWD" => $pwd,
            "Database" => $databaseName,
            "CharacterSet" => "UTF-8"
        ];

        /* Connect using SQL Server Authentication. */
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if ($conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        return $conn;
    }
}
