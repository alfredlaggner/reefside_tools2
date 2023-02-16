<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\PhoneNumber;
use App\Models\TwilioResultLog;

class SmsLogImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collections)
    {
        ini_set("max_execution_time", 0);
        foreach ($collections as $row) {
            //   dd($row);
            $x = 10000000000;
            TwilioResultLog::updateOrCreate(
                ['sid' => $row['sid']],
                [
                    'from' => $row['from'] - $x,
                    'to' => $row['to'] -$x,
                    'sent_date' => $row['sentdate'],
                    'body' => $row['body'],
                    'status' => $row['status'],
                    'error_code' => $row['errorcode'],
                    'direction' => $row['direction'],
                    'price' => $row['price'],
                ]);
        }
    }

    public function chunkSize(): int

    {
        return 500;
    }

}
