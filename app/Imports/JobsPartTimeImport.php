<?php

namespace App\Imports;

use Botble\Life\Models\Jobs\JobsPartTime;
use Botble\Member\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;

class JobsPartTimeImport implements ToModel, WithCustomCsvSettings, WithStartRow, WithChunkReading, WithBatchInserts
{
    /**
     * @param array $row
     *
     * @return JobsPartTime|null
     */
    public function model(array $row)
    {
        //ini_set('max_execution_time', 3000);
        return new JobsPartTime([
            'id' => $row[0],
            'title' => $row[1],
            'categories' =>  ['1' => "1",'2' => "4"],
            'detail' => $row[4],
            'member_id' => $row[5],
            'lookup' => $row[7],
            'status' => $row[8],
            'created_at' => today(),
            'updated_at' =>today(),
            'published' => today(),
        ]);
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
