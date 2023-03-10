<?php

namespace App\Imports;

use Botble\Member\Models\Member;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MemberImport implements ToCollection, WithCustomCsvSettings, WithStartRow, WithChunkReading, WithBatchInserts
{
    public function collection(Collection $rows)
    {
        ini_set('max_execution_time', 3000);
        $rows = $rows->transform(function ($row) {
            $row[0] = (int) $row[0];
            $row[2] = empty($row[2]) ? 'N/A' : $row[2];
            $row[7] = empty($row[7]) ?  'welcomeEH' . $row[0] . '@gmail.com' : str_replace(' ', '', $row[7]);
            $row[8] =  (float) $row[8]  < 0  ? 0 :  round( (float) $row[8] ) ;
            return $row;
        });
        $ids = $rows->pluck(0)->toArray();
        $idsInDb = Member::select('id')->whereIn('id', $ids)->get()->pluck('id')->toArray();
        $a = array_diff($ids, $idsInDb);
        $rows1 = $rows->whereIn(0, $a)->values();

        $defaultPass = Hash::make(123456789);
        $data = $rows1->map(function ($row) use ($defaultPass) {
            return [
                'id' => $row[0],
                'id_login' => $row[1],
                'first_name' => $row[2],
                'fullname' => $row[2] ,
                'nickname' => $row[3],
                'password' => $row[4] ?? $defaultPass, // 123456789
                'passwd_enc' => $row[5],
                'email' => $row[7] ,
                'role_member_id' => 1,
                'created_at' => today(),
                'updated_at' => today(),
                'confirmed_at' => today(),
                'student_number' => $row[6] ,
                'point' => (int)$row[8] ,
            ];
        });

        if ($data->isNotEmpty()) {
            Member::insert($data->toArray());
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
