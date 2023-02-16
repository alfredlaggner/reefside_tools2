<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\PhoneNumber;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PhoneNumberImport2 implements WithHeadingRow, ToCollection, WithChunkReading
{

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collections)
    {
        ini_set("max_execution_time", 0);
        foreach ($collections as $row) {
            PhoneNumber::updateOrCreate(['phone' => $row['phone']],
                [
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                ]);
        }
    }
    public function chunkSize(): int

    {
        return 500;
    }
}
