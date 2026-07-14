<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForwardToSubDepartment extends Model
{
    use HasFactory;
    protected $table = 'forward_to_sub_dept';

    protected $fillable = ['reg_no','old_department_id','old_department_name','new_department_id','new_department_name','reason','meta_deta'];
}
