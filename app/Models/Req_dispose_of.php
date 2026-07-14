<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Req_dispose_of extends Model
{
    use HasFactory;

    protected $table = 'req_dispose_ofs';
    protected $fillable = ['reg_no', 'file_no', 'disposal_type', 'reason', 'document_name', 'document_path', 'original_doc_name','is_file_reuploaded','document_path1','document_path2','document_name1','document_name2','original_doc_name1','original_doc_name2'];

    public function submitRequest()
    {
        return $this->belongsTo(SubmitRequest::class, 'reg_no', 'reg_no');
    }
}
