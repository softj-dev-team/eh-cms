<?php

namespace App\Console\Commands;

use App\Traits\ConnectMySql;
use App\Traits\ConnectSql;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportEwhaClass extends Command
{
    use ConnectSql;
    use ConnectMySql;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:ewha-class';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import TBL_EWHA_CLASS from old DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->initMySql();
    }

    /**
     * Execute the console command.
     */
    public function handle() {
        set_time_limit(0);
        ini_set("memory_limit", -1);

        $ids = $this->insertEvents();

        $this->insertComment($ids);
    }

    protected function insertEvents() {
        $limit = 200;
        $index = 0;

        $listId = [];

        while (true) {
            $conn = $this->connectSqlServer('EWHAIAN');

            $offset = $index * $limit;

            $tsql = "SELECT [idx],[v_idx],[G_YEAR_HAKKI],[G_GUBUN],[G_AREA],[G_HAKKWA],[G_NUM]
                  ,[G_BUNBAN],[G_NAME],[G_HAKNYUN],[G_GYOSU],[G_HAKJUM],[G_TIME],[G_GYOSI]
                  ,[G_WHERE],[G_BIGO]
              FROM [EWHAIAN].[dbo].[TBL_EWHA_CLASS]
              ORDER BY [TBL_EWHA_CLASS].[idx]
              OFFSET $offset ROWS
              FETCH NEXT $limit ROWS ONLY";

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

            if (empty($records)) {
                return $listId;
            }

            /* Free statement and connection resources. */
            sqlsrv_free_stmt($stmt);
            sqlsrv_close($conn);

            // Execute
            $conn = $this->connectMySql();
            $ids = [];

            $sql = 'INSERT INTO `evaluation` (`id`, `title`, `content`, `professor_name`, `semester`,
                          `score`, `grade`, `remark`, `lookup`, `status`, `created_at`, `updated_at`,
                          `datetime`, `course_code`, `lecture_room`, `compete`, `class_type`,
                          `department`, `division`, `class_hours`, `major_type`) VALUES ';

            foreach ($records as $row) {
                // edit
                $id = $row[0];
                $ids[] = $id;
                $semester = $row[2];
                $classType = $row[3];
                $majorType = $row[4];
                $department = $row[5];
                $courseCode = $row[6];
                $division = $row[7];
                $title = $this->mysqli->real_escape_string(html_entity_decode($row[8]));
                $grade = $row[9];
                $professorName = $row[10];
                $score = $row[11];
                $classHours = $row[12];
                $datetime = $row[13];
                $lectureRoom = $row[14];
                $dateCreate = Carbon::now()->format('Y-m-d');
                $remark = $this->mysqli->real_escape_string(html_entity_decode($row[15]));
                $sql .= "('$id','$title',NULL,'$professorName','$semester','$score','$grade','$remark','0','publish','$dateCreate','$dateCreate','$datetime','$courseCode','$lectureRoom',NULL,'$classType','$department','$division','$classHours','$majorType'),";
            }

            $sql = substr($sql, 0, -1);

            $index++;

            var_dump($offset);
            echo "\n\n";

            $conn->exec($sql);

            $listId[] = implode(',', $ids);
        }
    }

    protected function insertComment($ids) {
        foreach ($ids as $id) {
            $conn = $this->connectSqlServer('EWHAIAN');

            $commentSql = "SELECT [R_IDX],[V_IDX],[MEM_IDX],[MEM_NICK],[MEM_ID],[R_TITLE],[R_COMMENT]
                  ,[R_VALUE],[R_DATE],[R_COUNT],[R_GOOD],[R_REPLE],[MEM_IP]
              FROM [EWHAIAN].[dbo].[TBL_EWHA_CLASS_VALUE_RESULT]
              WHERE V_IDX IN ($id)";

            /* Execute the query. */
            $stmt = sqlsrv_query($conn, $commentSql);

            if (!$stmt) {
                echo "Error in statement execution.\n";
                die(print_r(sqlsrv_errors(), true));
            }

            /* Iterate through the result set printing a row of data upon each iteration.*/
            $records = [];

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
                $records[] = $row;
            }

            if (empty($records)) {
                continue;
            }

            /* Free statement and connection resources. */
            sqlsrv_free_stmt($stmt);
            sqlsrv_close($conn);

            // Execute
            $conn = $this->connectMySql();

            $sql = 'INSERT INTO `comments_evaluation` (`id`, `evaluation_id`, `votes`, `member_id`, `comments`,
                                   `created_at`, `updated_at`, `grade`, `assignment`, `attendance`,
                                   `textbook`, `team_project`, `number_times`, `type`, `ip_address`) VALUES ';

            foreach ($records as $row) {
                $id = $row[0];
                $evaluationId = $row[1];
                $memberId = $row[2];
                $comments = $this->mysqli->real_escape_string(html_entity_decode($row[6]));
                $date = $row[8]->format('Y-m-d H:i:s');
                $sql .= "('$id','$evaluationId',0,'$memberId','$comments','$date','$date',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),";
            }

            $sql = substr($sql, 0, -1);

            echo "insert comments \n\n";

            $conn->exec($sql);
        }
    }
}
