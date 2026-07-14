<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    use HasFactory;
    public $fillable = [
        ['name' => '10th Pass','degree' => 'SSC','metaData' => '',],
        ['name' => '12th Pass', 'degree' => 'HSSC', 'metaData' => '',],
        ['name' => 'Bachlors Degree', 'degree' => 'Graduate', 'metaData' => '',],
        ['name' => 'Masters Degree', 'degree' => 'Post Graduate', 'metaData' => '',],
    ];
}
