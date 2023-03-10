<?php

namespace App\Imports;

use Botble\Member\Models\Member;
use Botble\Member\Models\MemberCareer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MemberCareerImport implements ToModel, WithCustomCsvSettings, WithStartRow, WithChunkReading, WithBatchInserts
{
    /**
     * @param array $row
     *
     * @return Member|null
     */
    public function model(array $row)
    {
        return new MemberCareer([
            'mem_idx' => $row[0],
            'login_count' => $row[1],
            'good_s_count' => $row[3],
            'gubak_s_count' => $row[4],
            'good_r_count' => $row[6],
            'gubak_r_count' => $row[7],
            'board_count' => $row[8],
            'reple_count' => $row[9],
            'biwon_board_count' => $row[10],
            'biwon_reple_count' => $row[11],
            'hooli_s_count' => $row[12],
            'hooli_r_count' => $row[13],
            'last_login' => $row[15],
            'first_date' => $row[16],
            'rgood_r_count' => $row[17],
            'rgood_s_count' => $row[18],
            'rgubak_r_count' => $row[19],
            'rgubak_s_count' => $row[20],
            'bgood_r_count' => $row[21],
            'bgood_s_count' => $row[22],
            'bgubak_r_count' => $row[23],
            'bgubak_s_count' => $row[24],
            'brgood_r_count' => $row[25],
            'brgood_s_count' => $row[26],
            'brgubak_r_count' => $row[27],
            'brgubak_s_count' => $row[28],
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
