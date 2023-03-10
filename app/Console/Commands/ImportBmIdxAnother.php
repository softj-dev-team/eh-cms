<?php

namespace App\Console\Commands;

use App\Traits\ConnectMySql;
use App\Traits\ConnectSql;
use Illuminate\Console\Command;

class ImportBmIdxAnother extends Command
{
    use ConnectSql;
    use ConnectMySql;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:bm-idx-another';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import BM_IDX_ANOTHER from old DB';

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

        $ids = $this->insertComment($ids);

        $this->insertReactStatusComment($ids, 'like');

        $this->insertReactStatusComment($ids, 'dislike');
    }

    protected function insertEvents() {
        $limit = 1000;
        $index = 0;
        $type = implode(',', [4, 11, 33, 35]);

        $listId = [];

        while (true) {
            $conn = $this->connectSqlServer('EWHAIAN');

            $offset = $index * $limit;

            $tsql = "SELECT [BP_IDX]
                  ,[CT_IDX],[BM_IDX],[MEM_IDX],[MEM_ID],[MEM_NICK]
                  ,[BP_TITLE],[BP_CONTENT],[BP_COUNT],[BP_REPLE]
                  ,[BP_DATE],[BP_TYPE],[BP_MOBILE],[MEM_IP],[TEMP_IDX]
                  ,[BP_GOOD],[BP_GUBAK],[BP_LOVE],[BP_IMG]
              FROM [EWHAIAN].[dbo].[EWHA_BOARD_POST]
              WHERE BM_IDX NOT IN ($type)
              ORDER BY [EWHA_BOARD_POST].[BP_IDX]
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

            $sql = 'INSERT INTO `EWHA_BOARD_POST` (`BP_IDX`, `CT_IDX`, `BM_IDX`, `MEM_IDX`,
                               `MEM_ID`, `MEM_NICK`, `BP_TITLE`, `BP_CONTENT`, `BP_COUNT`,
                               `BP_REPLE`, `BP_DATE`, `BP_TYPE`, `BP_MOBILE`, `MEM_IP`, `TEMP_IDX`,
                               `BP_GOOD`, `BP_GUBAK`, `BP_LOVE`, `BP_IMG`) VALUES ';

            foreach ($records as $row) {
                $ids[] = $row[0];
                $row[1] = @$row[1] ?: 0;
                $row[5] = $this->mysqli->real_escape_string(html_entity_decode($row[5]));
                $row[6] = $this->mysqli->real_escape_string(html_entity_decode($row[6]));
                $row[7] = $this->mysqli->real_escape_string(html_entity_decode($row[7]));
                $row[10] = $row[10]->format('Y-m-d H:i:s');
                $row[14] = @$row[14] ?: 0;

                $joinValue = implode("','", $row);

                $sql .= "('$joinValue'),";
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
        $listId = [];

        foreach ($ids as $id) {
            $conn = $this->connectSqlServer('EWHAIAN');

            $commentSql = "SELECT [BR_IDX]
                  ,[BP_IDX], [BM_IDX], [BR_NUM], [BR_STEP], [MEM_IDX]
                  ,[MEM_ID], [MEM_NICK], [BR_CONTENT], [BR_TYPE], [BR_PWD]
                  ,[BR_GOOD], [BR_BAD], [V_IDX], [BR_DATE], [TEMP_IDX], [MEM_IP] ,[BR_LOVE]
              FROM [EWHAIAN].[dbo].[EWHA_BOARD_REPLE]
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
            $ids = [];

            $sql = 'INSERT INTO `EWHA_BOARD_REPLE` (`BR_IDX`, `BP_IDX`, `BM_IDX`, `BR_NUM`, `BR_STEP`, `MEM_IDX`,
                                `MEM_ID`, `MEM_NICK`, `BR_CONTENT`, `BR_TYPE`, `BR_PWD`, `BR_GOOD`, `BR_BAD`,
                                `V_IDX`, `BR_DATE`, `TEMP_IDX`, `MEM_IP`, `BR_LOVE`) VALUES ';

            foreach ($records as $row) {
                $ids[] = $row[0];
                $row[7] = $this->mysqli->real_escape_string(html_entity_decode($row[7]));
                $row[8] = $this->mysqli->real_escape_string(html_entity_decode($row[8]));
                $row[14] = $row[14]->format('Y-m-d H:i:s');
                $row[15] = @$row[15] ?: 0;
                $row[17] = @$row[17] ?: 0;

                $joinValue = implode("','", $row);

                $sql .= "('$joinValue'),";
            }

            $sql = substr($sql, 0, -1);

            echo "insert comments \n\n";

            $conn->exec($sql);

            $listId[] = implode(',', $ids);
        }

        return $listId;
    }

    protected function insertReactStatusComment($ids, $reactStatus) {
        switch ($reactStatus) {
            case 'like':
                $table = 'REPLE_GOOD_LOG';
                break;

            case 'dislike':
                $table = 'REPLE_BAD_LOG';
                break;
        }

        foreach ($ids as $id) {
            $conn = $this->connectSqlServer('EWHAIAN_LOG');

            $commentSql = "SELECT [MEM_IDX]
                  ,[BR_IDX]
                  ,[L_DATE]
                  ,[BR_MEM_IDX]
              FROM [EWHAIAN_LOG].[dbo].[$table]
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

            $sql = "INSERT INTO `$table` (`MEM_IDX`, `BR_IDX`, `L_DATE`, `BR_MEM_IDX`) VALUES ";

            foreach ($records as $row) {
                $row[2] = $row[2]->format('Y-m-d H:i:s');

                $joinValue = implode("','", $row);

                $sql .= "('$joinValue'),";
            }

            $sql = substr($sql, 0, -1);

            echo "insert sympathy \n\n";

            $conn->exec($sql);
        }
    }
}
