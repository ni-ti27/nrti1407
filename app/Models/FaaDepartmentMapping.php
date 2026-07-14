<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaaDepartmentMapping extends Model
{
    use HasFactory;
    protected $table = 'faa_dept_mapping';

    protected $fillable = [
        'dept_code',
        'office_code',
        'suboffice_code',
        'dept_id',
        'fadept_code',
        'faoffice_code',
        'fasuboffice_code',
        'fadept_id',
        'active',
    ];
}
