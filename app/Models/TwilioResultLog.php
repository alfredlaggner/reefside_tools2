<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PhoneNumber;
class TwilioResultLog extends Model
{
    use HasFactory;

    protected $fillable = ['sid',
        'from',
        'to',
        'sent_date',
        'body',
        'status',
        'error_code',
        'direction',
        'price'];

    public function phone_to()
    {
        return $this->hasOne(PhoneNumber::class, 'phone', 'to');
    }
    public function phone_from()
    {
        return $this->hasOne(PhoneNumber::class, 'phone', 'from');
    }


}

