<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaActions extends Model
{
    use HasFactory;

    protected $fillable = [
        'fa_reg_no','action_id', 'fa_action_details_id','disposal_reason','spio_comments','faa_comments','fa_reply_doc','document_name','document_original_name','document_path','return_to_applicant_reason'
    ];

    public function fa_submit_request()
    {
        return $this->belongsTo(fa_submit_request::class, 'fa_reg_no', 'fa_reg_no');
    }
}
