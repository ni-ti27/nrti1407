<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class application_history extends Model
{
    use HasFactory;
    protected $table = 'application_history';

    public $fillable = [
        'reg_no',
        'changes',
        'history_field',
        'action_details_id',
        'current_dept_id'
    ];
}
