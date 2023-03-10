<?php

namespace App\Imports;

use Botble\Campus\Models\Evaluation\Evaluation;
use Botble\Campus\Models\Evaluation\Major;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EvaluationImport implements ToCollection, WithCustomCsvSettings, WithStartRow, WithChunkReading, WithBatchInserts
{
    private $days = [
        '월' => 'mon',
        '화' => 'tue',
        '수' => 'wed',
        '목' => 'thu',
        '금' => 'fri',
        '토' => 'sat',
        '일' => 'sun'
    ];

    public function collection(Collection $rows)
    {
        ini_set('max_execution_time', 3000);
        $rows = $rows->transform(function ($row) {

            $valMajor = trim($row[5]);
            $major = null;
            if($valMajor){
                $major =  Major::where('name', $valMajor )->get()->first();
                if(!$major){
                    $major = Major::create(['name' => $valMajor, 'parents_id' => 1]);
                }
            }
            $majorArrId = $major ? [$major->id] : [];
            $row[5] = $majorArrId;
            $arrDatetime = [];
            if(!empty($row[10])){
                $valDatetime1 = array_map('trim', explode('/', $row[10]));
                foreach ($valDatetime1 as $item){
                    $valItem = array_map('trim', explode('-',$item));
                    $arrDatetime[] = [
                        'day' => $valItem[0],
                        'from' =>  $this->convertTime($valItem[1]),
                        'to' =>  $this->convertTime($valItem[2]),
                    ];
                }
            }

            $row[10] = $arrDatetime ? json_encode($arrDatetime) : null ;
            $row[11] = !empty($row[11]) ? 1 : 0;
            $row[13] = !empty($row[13]) ? 1 : 0;
            $row[14] = !empty($row[14]) ? 1 : 0;
            return $row;
        });

        $data = $rows->map(function ($row) {
            return [
                'course_code' => $row[0],
                'division' => $row[1],
                'title' => $row[2],
                'class_type' => $row[3],
                'major_type' => $row[4] ,
                'major' => $row[5],
                'grade' => $row[6] ,
                'professor_name' => $row[7] ,
                'score' => $row[8] ,  // credit
                'class_hours' => $row[9] ,
                'datetime' => $row[10],
                'status' => $row[10] ? 'publish' : 'draft'  ,
                'english_class' => $row[11] , // boolean
                'foreign_language' => $row[12] ,
                'humanities_class' => $row[13] , // boolean
                'sw_class' => $row[14] , // boolean
                'quota' => (int)$row[15] ,
                'online_class' => $row[16] ,
                'lecture_room' => $row[17] ,
                'remark' => $row[18] ,
                'created_at' => today(),
                'updated_at' => today(),
            ];
        });


        if ($data->isNotEmpty()) {
            foreach ($data as $item){
                $major = $item['major'];
                unset($item['major']);
                $evaluation = Evaluation::create($item);
                $evaluation->major()->sync($major);
            }
//            Evaluation::insert($data->toArray());
        }

    }
    public  function convertTime($data){
        $time = explode(':', $data);
        $minute = (int)$time[1];
        if($minute == 45) {
            $hourMinute = 1;
        }elseif ($minute == 15){
            $hourMinute = 0;
        }elseif($minute == 30){
            $hourMinute = 0.5;
        }else{
            $hourMinute = 0;
        }
        $hours = (int)$time[0]  + $hourMinute;
        return $hours;
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
