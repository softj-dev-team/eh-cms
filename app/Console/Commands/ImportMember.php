<?php

namespace App\Console\Commands;

use Botble\Member\Models\Member;
use Illuminate\Console\Command;

class ImportMember extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import member from old DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle() {
        set_time_limit(0);
        ini_set("memory_limit", -1);

        $conn = $this->connectSqlServer();

        $tsql = "SELECT [TBL_MEMBER_INFO].[MEM_IDX]
      ,[MEM_ID]
      ,[MEM_NAME]
      ,[MEM_NICK]
      ,[MEM_PWD]
      ,[MEM_NUM]
      ,[MEM_EMAIL]
  FROM [EWHAIAN].[dbo].[TBL_MEMBER_INFO]
  INNER JOIN [EWHAIAN].[dbo].[TBL_MEMBER_ADDINFO] ON TBL_MEMBER_INFO.MEM_IDX = TBL_MEMBER_ADDINFO.MEM_IDX";

        /* Execute the query. */

        $stmt = sqlsrv_query($conn, $tsql);

        if (!$stmt) {
            echo "Error in statement execution.\n";
            die(print_r(sqlsrv_errors(), true));
        }

        /* Iterate through the result set printing a row of data upon each iteration.*/
        $records = [];

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
            $records[] = $row;
        }

        $chunks = array_chunk($records, 1000);

        /* Free statement and connection resources. */
        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);

        // Execute
        $conn = $this->connectMySql();

        $mysqli = new \mysqli('115.77.189.247:33070', 'root','01Brickmate23!@', 'eh');

        foreach ($chunks as $chunk) {
            $sql = 'INSERT INTO members(`id`, `id_login`, `first_name`, `fullname`, `nickname`, `password`, `student_number`, `email`, `role_member_id`) VALUES ';

            foreach ($chunk as $row) {
                $password = bcrypt($row[4]);
                $row[3] = $mysqli->real_escape_string($row[3]);

                $sql .= "('$row[0]','$row[1]','$row[2]','$row[2]','$row[3]','$password','$row[5]','$row[6]','1'),";
            }

            $sql = substr($sql, 0, -1);

            var_dump($sql);
            echo "\n\n";

            $conn->exec($sql);
        }
    }

    public function connectSqlServer() {
        $serverName = "115.77.189.247";
        $uid = "sa";
        $pwd = "L7G4khHjnHSzEEsm";
        $databaseName = "EWHAIAN";

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

    public function connectMySql() {
        $servername = "115.77.189.247:33070";
        $username = "root";
        $password = "01Brickmate23!@";
        $dbname = "eh";

        try {
            $conn = new \PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            return $conn;

        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
