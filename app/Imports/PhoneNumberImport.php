<?php
namespace App\Imports;

use App\Models\PhoneNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
class PhoneNumberImport implements ToModel, WithChunkReading

{


    /**
    * @param array $row
    *
    * @return PhoneNumber
     */
    public function model(array $row)
    {
        return new PhoneNumber([
            'first_name'     => $row[4],
            'last_name'     => $row[5],
            'phone'     => $row[3],
            'external_id'    => $row[0],
        ]);
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}
