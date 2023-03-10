<?php

namespace App\Imports;

use Botble\Member\Models\Member;
use Botble\Member\Models\MemberAddInfo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MemberAddInfoImport implements ToModel, WithCustomCsvSettings, WithStartRow, WithChunkReading, WithBatchInserts
{
    /**
     * @param array $row
     *
     * @return Member|null
     */
    public function model(array $row)
    {
        return new MemberAddInfo([
            'mem_idx' => $row[0],
            'mem_email' => $row[3],
            'mem_addr' => $row[4],
            'mem_post' => $row[5],
            'mem_phone' => $row[6],
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
