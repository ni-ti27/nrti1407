<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferToAnotherDepartment extends Model
{
    use HasFactory;
    protected $table = 'transfer_to_another_departments';

    protected $fillable = ['old_department_id', 'new_department_id', 'old_department_name', 'new_department_name','old_reg_no','new_reg_no', 'meta_deta','reason'];
}
