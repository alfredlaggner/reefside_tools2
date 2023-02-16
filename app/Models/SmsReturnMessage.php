<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsReturnMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'phone_to',
        'phone_from',
        'body',
        'status',
        'result_id',
        'date_sent',
        'date_created',
    ];
}
