<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsStatusCallback extends Model
{
    use HasFactory;


    protected $table = 'sms_status_callback';
    protected $fillable = ['to', 'message_id', 'status'];
}
