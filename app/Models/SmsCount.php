<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsCount extends Model
{
    use HasFactory;

    protected $table = 'sms_count';
    protected $fillable = ['count', 'start_date', 'last_update_date'];
    public $timestamps = false;
}
