<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
class PhoneNumber extends Model
{
    use HasFactory;
    use Importable;

    protected $table = 'phone_numbers';
    protected $fillable = ['first_name','last_name','phone','email','is_valid','is_cancelled'];

    public function twilio_log()
    {
        return $this->belongsTo(TwilioResultLog::class, "to", 'phone');
    }
}
