<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class SmsCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'send_date','sent_date','message','number_valid','current_steps','number_sent','number_refused','phone_number_file',
    ];

/*    protected function data(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }*/
}
