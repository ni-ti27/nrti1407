<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtiApplicantRegdata extends Model
{
    use HasFactory;
    protected $table = 'rti_applicant_regdata';
    protected $fillable = [
        'regmobile_no',
        'email_id',
        'dept_id',
        'reg_no',
    ];
}
