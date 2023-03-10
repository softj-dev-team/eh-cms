<?php

namespace App\Console\Commands;

use Throwable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CombineGardens extends Command
{
    const GARDENS_TABLE_BK = ['gardens_bk', 'gardens_bk_3', 'gardens_bk_4', 'gardens_bk_5'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'combine:garden {--table=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Combine gardens_bk, gardens_bk_3, gardens_bk_4, gardens_bk_5 to gardens';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('max_execution_time', -1);
        $table = null;
        $options = $this->options();
        if (empty($options['table'])) {
            $table = $this->ask('What is your table?');
        } else {
            $table = $options['table'];
        }

        if (!in_array($table, self::GARDENS_TABLE_BK)) {
            echo 'Table is incorrect';
            return;
        }

        switch ($table) {
            case 'gardens_bk':
                $this->insertGardenTables($table, 'comments_gardens_bk', 'sympathy_gardens_bk');
                break;
            case 'gardens_bk_3':
                $this->insertGardenTables($table, 'comments_gardens_bk_3', 'sympathy_gardens_bk_3');
                break;
            case 'gardens_bk_4':
                $this->insertGardenTables($table, 'comments_gardens_bk_4', 'sympathy_gardens_bk_4');
                break;
            case 'gardens_bk_5':
                $this->insertGardenTables($table, 'comments_gardens_bk_5', 'sympathy_gardens_bk_5');
                break;
        }
    }

    protected function insertGardenTables($table, $commentTable, $sympathyTable)
    {
        DB::table($table)->where('is_combine', '!=', 1)->orderBy('id')->chunkById(200, function ($gardenBks) use ($commentTable, $sympathyTable, $table) {
            foreach ($gardenBks as $gardenBk) {
                $gardenBkId = $gardenBk->id;

                // update combine
                DB::table($table)
                    ->where('id', $gardenBkId)
                    ->update(['is_combine' => 1]);

                $gardenBkData = (array) $gardenBk;
                unset($gardenBkData['id']);
                unset($gardenBkData['is_combine']);
                $commentGardenBkDatas = [];
                $sympathyGardenBkDatas = [];
                $commentGardenBks = DB::table($commentTable)
                    ->where('gardens_id', $gardenBkId)
                    ->orderBy('id')
                    ->get();
                if (count($commentGardenBks)) {
                    $commentGardenBkDatas = $commentGardenBks->toArray();
                    foreach ($commentGardenBkDatas as $key => $commentGardenBkData) {
                        $commentGardenBkDatas[$key] = (array) $commentGardenBkData;
                    }
                }

                $sympathyGardenBks = DB::table($sympathyTable)
                    ->where('gardens_id', $gardenBkId)
                    ->orderBy('id')
                    ->get();
                if (count($sympathyGardenBks)) {
                    $sympathyGardenBkDatas = $sympathyGardenBks->toArray();
                    foreach ($sympathyGardenBkDatas as $key => $sympathyGardenBkData) {
                        $sympathyGardenBkDatas[$key] = (array) $sympathyGardenBkData;
                    }
                }

                try {
                    DB::beginTransaction();
                    $gardenId = DB::table('gardens')->insertGetId($gardenBkData);
                    if (count($commentGardenBkDatas)) {
                        foreach ($commentGardenBkDatas as &$commentGardenBkData) {
                            $commentGardenBkData['gardens_id'] = $gardenId;
                            $commentGardenBkData['is_deleted'] = false;
                        }

                        DB::table('comments_gardens')->insertOrIgnore($commentGardenBkDatas);
                    }

                    if (count($sympathyGardenBkDatas)) {
                        foreach ($sympathyGardenBkDatas as &$sympathyGardenBkData) {
                            $sympathyGardenBkData['gardens_id'] = $gardenId;
                        }

                        DB::table('sympathy_gardens')->insertOrIgnore($sympathyGardenBkDatas);
                    }

                    DB::commit();
                } catch (Throwable $th) {
                    DB::rollBack();
                    Log::error($th->getMessage());
                    break;
                }
            }
        });

        $msg = 'Insert '. $table .' to gardens successfully.';
        Log::info($msg);
        echo $msg;
    }
}
