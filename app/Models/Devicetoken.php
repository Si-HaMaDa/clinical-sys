<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devicetoken extends Model
{
    public $table = 'devicetokens';

    public $fillable = [
        'device_token',
        'os_type',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
