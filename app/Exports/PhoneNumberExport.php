<?php

namespace App\Exports;

use App\Models\PhoneNumber;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PhoneNumberExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        return PhoneNumber::all();
    }
    public function headings(): array {
        return [
           "id" ,"first_name","last_name","phone", "is_valid"
        ];
    }

}
