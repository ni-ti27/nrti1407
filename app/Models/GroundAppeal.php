<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroundAppeal extends Model
{
    use HasFactory;
    // protected $table = 'ground_appeals';
    protected $fillable = ['name'];
}
