<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckInLog extends Model
{
    protected $table = 'check_ins_log';

    protected $fillable = [];

    protected $guarded = [];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];
}
