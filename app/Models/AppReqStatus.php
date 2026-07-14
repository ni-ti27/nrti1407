<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppReqStatus extends Model
{
    use HasFactory;
    // protected $table = 'rti_application_status';
    protected $table = 'rti_status_new_req';
}