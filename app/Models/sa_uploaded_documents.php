<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sa_uploaded_documents extends Model
{
    use HasFactory;
    protected $table = 'sa_uploaded_documents';

    protected $fillable = [
        'sa_reg_no', 'document_category', 'doc_name', 'doc_path', 'doc_original_name', 'meta_data',
    ];

    public function saSubmitReq()
    {
        return $this->belongsTo(sa_submit_request::class, 'sa_reg_no', 'sa_reg_no');
    }
}
