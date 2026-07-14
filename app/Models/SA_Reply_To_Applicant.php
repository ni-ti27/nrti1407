<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SA_Reply_To_Applicant extends Model
{
    use HasFactory;

    protected $table = 'sa_reply_to_applicants';

    public $fillable = [
        'sa_reg_no', 'reason', 'meta_data'
    ];

    public function submitReqRelation()
    {
        return $this->belongsTo(sa_submit_request::class, 'sa_reg_no', 'sa_reg_no');
    }
}
