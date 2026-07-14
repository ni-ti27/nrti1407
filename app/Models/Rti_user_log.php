<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rti_user_log extends Model
{
    use HasFactory;
    protected $table = 'rti_user_logs';

    protected $fillable = [
        'user_id',
        'applicant_name',
        'regmobile_no',
        'email_id',
        'logindatetime',
        'logOutdatetime',
        'loggedin_ip'
    ];
}
