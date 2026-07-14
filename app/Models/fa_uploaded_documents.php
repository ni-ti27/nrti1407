<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fa_uploaded_documents extends Model
{
    use HasFactory;

    protected $table = 'fa_uploaded_documents';
    protected $fillable = ['reg_no', 'document_category', 'document_name', 'document_path', 'document_upload_date', 'document_name_original'];

    public function request()
    {
        return $this->belongsTo(fa_submit_request::class, 'reg_no', 'reg_no');
    }

    public function category()
    {
        return $this->belongsTo(fa_document_category::class, 'document_category', 'document_code');
    }

    public function documentCategory()
    {
        return $this->belongsTo(fa_document_category::class,'document_category','id');
    }
}
