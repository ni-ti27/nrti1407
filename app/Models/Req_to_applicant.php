<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Req_to_applicant extends Model
{
    use HasFactory;

    protected $table = 'req_to_applicants';
    protected $fillable = [
        'reg_no',
        'reason',
        'meta_data',
    ];

    public function submitRequest()
    {
        return $this->belongsTo(SubmitRequest::class, 'reg_no', 'reg_no');
    }
}
