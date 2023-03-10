<?php

namespace App\Imports;

use Botble\Member\Models\Member;
use Botble\Member\Models\MemberAuthKey;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MemberAuthKeyImport implements ToModel, WithCustomCsvSettings, WithStartRow, WithChunkReading, WithBatchInserts
{
    /**
     * @param array $row
     *
     * @return Member|null
     */
    public function model(array $row)
    {
        return new MemberAuthKey([
            'mem_idx' => $row[0],
            'auth_key' => $row[1],
            'biwon_key' => $row[2],
            'reg_date' => $row[3],
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
