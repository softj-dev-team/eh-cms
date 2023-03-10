<?php

namespace App\Imports;

use Botble\Campus\Models\Schedule\Schedule;
use Botble\Campus\Models\Schedule\ScheduleTimeLine;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ScheduleImport implements ToCollection, WithCustomCsvSettings, WithStartRow, WithChunkReading, WithBatchInserts
{
    public function collection(Collection $rows)
    {
        ini_set('max_execution_time', 3000);
        $rows = $rows->transform(function ($row) {

            if(!empty($row[2])){
                if (is_numeric($row[2])) {
                    try{
                        $row[2] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2]))->toDateString();
                    }catch (\Throwable $e) {
                        $row[2] = -1 ;
                    }
                } else{
                    try{
                        $row[2] = Carbon::make($row[2])->toDateString();
                    } catch (\Throwable $e) {
                        $row[2] = -1 ;
                    }
                }

            }else{
                $row[2] = null  ;
            }

            if(!empty($row[3])){
                if (is_numeric($row[3])) {
                    try{
                        $row[3] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]))->toDateString();
                    }catch (\Throwable $e) {
                        $row[3] = -1 ;
                    }
                } else{
                    try{
                        $row[3] = Carbon::make($row[3])->toDateString();
                    } catch (\Throwable $e) {
                        $row[3] = -1 ;
                    }
                }

            }else{
                $row[3] = null  ;
            }
            return $row;
        });

        foreach($rows as $row){

            $scheduleData = [
                'name' => $row[0],
                'total_credit' => $row[1],
                'id_login' => $row[4] ?? 0,
                'start' => $row[2] ?? today(),
                'end' => $row[3] ?? today(),
                'status' =>'publish',
                'created_at' => today(),
                'updated_at' => today(),
            ];

            $schedule = Schedule::create($scheduleData);
            $timelines = $row[5] ? json_decode($row[5], TRUE) : [];

            foreach( $timelines as &$item) {
                $item['schedule_id'] = $schedule->id;
                $item['datetime'] = json_encode($item['datetime']);
            }
            if($timelines){
                ScheduleTimeLine::insert($timelines);
            }

        }

    }
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ",",
            'enclosure' => "'",

        ];
    }
    public function startRow(): int
    {
        return 2;
    }
    public function chunkSize(): int
    {
        return 1000;
    }
    public function batchSize(): int
    {
        return 1000;
    }
}
