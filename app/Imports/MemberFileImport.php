<?php

namespace App\Imports;

use Botble\Member\Models\Member;
use Botble\Member\Models\MemberFile;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MemberFileImport implements ToModel, WithCustomCsvSettings, WithStartRow, WithChunkReading, WithBatchInserts
{
    /**
     * @param array $row
     *
     * @return Member|null
     */
    public function model(array $row)
    {
        return new MemberFile([
            'f_idx' => $row[0],
            'mem_idx' => $row[1],
            'folder_idx' => $row[2],
            'f_name' => $row[3],
            'f_update' => $row[4],
            'f_upfilename' => $row[5],
            'f_upfileext' => $row[6],
            'f_date' => $row[7],
            'f_size' => $row[8],
            'f_open' => $row[9],
            'f_count' => $row[10],
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
