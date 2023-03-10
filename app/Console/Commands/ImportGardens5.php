<?php

namespace App\Console\Commands;

use App\Traits\ConnectMySql;
use App\Traits\ConnectSql;
use Illuminate\Console\Command;

class ImportGardens5 extends Command
{
    use ConnectSql;
    use ConnectMySql;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:gardens-5';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import garden_category_id 5 from old DB';

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

        $index = 0;
        $total = 1000;

        while (true) {
            $ids = $this->insertEvents($total, $index);

            $ids = $this->insertComment($ids);

            $this->insertReactStatusComment($ids, 'like');

            $this->insertReactStatusComment($ids, 'dislike');
        }
    }

    protected function insertEvents($total, &$index) {
        $limit = 1000;
        $count = 0;
        $categoryEventId = 5;

        $listId = [];

        while ($count < $total) {
            $conn = $this->connectSqlServer('BIWON');

            $offset = $index * $limit;
            $count += $limit;

            $tsql = "SELECT [EWHA_OLD_POST].[BP_IDX],[MEM_IDX],[BP_TITLE],[BP_CONTENT],[BP_COUNT],[BP_REPLE]
                  ,[BP_DATE],[BP_TYPE],[BP_MOBILE],[MEM_IP],[TEMP_IDX],[BP_GOOD],[BP_GUBAK]
                  ,[BP_LOVE],[BP_IMG],[S_PWD]
              FROM [BIWON].[dbo].[EWHA_OLD_POST]
              LEFT JOIN [BIWON].[dbo].[EWHA_BIWON_SECRET] ON EWHA_BIWON_SECRET.BP_IDX = EWHA_OLD_POST.BP_IDX
              ORDER BY [EWHA_OLD_POST].[BP_IDX]
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

            $sql = 'INSERT IGNORE INTO `gardens_bk_5` (`id`, `title`, `detail`, `notice`, `lookup`, `right_click`,
                          `active_empathy`, `member_id`, `categories_gardens_id`, `status`, `created_at`,
                          `updated_at`, `hot_garden`, `published`, `file_upload`, `link`, `hint`,
                          `can_reaction`, `pwd_post`) VALUES ';

            foreach ($records as $row) {
                $id = $row[0];
                $ids[] = $id;
                $memberId = $row[1];
                $title = $this->mysqli->real_escape_string($row[2]);
                $detail = $this->mysqli->real_escape_string($row[3]);
                $lookup = $row[4];
                $date = $row[6]->format('Y-m-d H:i:s');

                if (isset($row[15])) {
                    $password = bcrypt($row[15]);
                    $sql .= "('$id', '$title', '$detail', NULL, '$lookup', '0', '0', '$memberId', '$categoryEventId', 'publish', '$date', '$date', '1', '$date', NULL, NULL, NULL, '1', '$password'),";
                } else {
                    $sql .= "('$id', '$title', '$detail', NULL, '$lookup', '0', '0', '$memberId', '$categoryEventId', 'publish', '$date', '$date', '1', '$date', NULL, NULL, NULL, '1', NULL),";
                }
            }

            $sql = substr($sql, 0, -1);

            $index++;

            $conn->exec($sql);

            $to = $offset + $limit;

            echo "-------------------------------------------- \n";
            echo "$offset - $to \n\n";

            $listId[] = implode(',', $ids);
        }

        return $listId;
    }

    protected function insertComment($ids) {
        $listId = [];

        foreach ($ids as $id) {
            $conn = $this->connectSqlServer('EWHAIAN');

            $commentSql = "SELECT [BR_IDX]
                  ,[BP_IDX],[BR_NUM],[BR_STEP],[MEM_IDX],[BR_CONTENT]
                  ,[BR_TYPE],[BR_PWD],[BR_GOOD],[BR_BAD],[V_IDX],[BR_DATE]
                  ,[TEMP_IDX],[MEM_IP]
              FROM [BIWON].[dbo].[EWHA_OLD_REPLE]
              WHERE BP_IDX IN ($id)";

            /* Execute the query. */
            $stmt = sqlsrv_query($conn, $commentSql);

            if (!$stmt) {
                echo "Error in statement execution.\n";
                die(print_r(sqlsrv_errors(), true));
            }

            /* Iterate through the result set printing a row of data upon each iteration.*/
            $records = [];

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
                $key = $row[1] . '-' . $row[2];

                if (empty($records[$key])) {
                    $records[$key] = [$row];
                } else {
                    $records[$key][] = $row;
                }
            }

            if (empty($records)) {
                continue;
            }

            /* Free statement and connection resources. */
            sqlsrv_free_stmt($stmt);
            sqlsrv_close($conn);

            // Execute
            $conn = $this->connectMySql();
            $ids = [];

            $sql = 'INSERT IGNORE INTO `comments_gardens_bk_5` (`id`, `gardens_id`, `parents_id`, `content`,
                                   `status`, `created_at`, `updated_at`, `member_id`, `anonymous`,
                                   `ip_address`) VALUES ';

            foreach ($records as $record) {
                $parentsId = 'NULL';

                foreach ($record as $row) {
                    if ($row[3] == 0) {
                        $parentsId = $row[0];
                    }
                }

                foreach ($record as $row) {
                    $id = $row[0];
                    $ids[] = $id;
                    $eventId = $row[1];
                    $pId = $row[3] == 0 ? 'NULL' : $parentsId;
                    $memberId = $row[4];
                    $content = $this->mysqli->real_escape_string(html_entity_decode($row[5]));
                    $date = $row[11]->format('Y-m-d H:i:s');
                    $ip = $row[13];
                    $sql .= "('$id','$eventId',$pId,'$content','publish','$date','$date','$memberId','0','$ip'),";
                }
            }

            $sql = substr($sql, 0, -1);

            $conn->exec($sql);

            echo "insert comments \n\n";

            $listId[] = implode(',', $ids);
        }

        return $listId;
    }

    protected function insertReactStatusComment($ids, $reactStatus) {
        $isDislike = 0;

        switch ($reactStatus) {
            case 'like':
                $table = 'OLD_REPLE_GOOD_LOG';
                $isDislike = 0;
                break;

            case 'dislike':
                $table = 'OLD_REPLE_BAD_LOG';
                $isDislike = 1;
                break;
        }

        foreach ($ids as $id) {
            $conn = $this->connectSqlServer('BIWON');

            $commentSql = "SELECT [MEM_IDX]
                  ,[BR_IDX]
                  ,[L_DATE]
                  ,[BP_MEM_IDX]
              FROM [BIWON].[dbo].[$table]
              WHERE BR_IDX IN ($id)";

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

            $sql = 'INSERT IGNORE INTO `sympathy_gardens_bk_5` (`gardens_id`, `member_id`,
                                       `is_dislike`, `reason`, `created_at`, `updated_at`) VALUES ';

            foreach ($records as $row) {
                $memberId = $row[0];
                $commentId = $row[1];
                $date = $row[2]->format('Y-m-d H:i:s');
                $sql .= "($commentId,'$memberId','$isDislike','','$date','$date'),";
            }

            $sql = substr($sql, 0, -1);

            $conn->exec($sql);

            echo "insert sympathy $reactStatus \n\n";
        }
    }
}
