<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;
    public function taluka()
    {
        return $this->belongsTo(Taluka::class);
    }

    public function submitRequests()
    {
        return $this->hasMany(SubmitRequest::class);
    }
}
