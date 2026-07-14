<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSubDept extends Model
{
    use HasFactory;
    public function subDept()
    {
        return $this->belongsTo(SubDept::class);
    }
}
