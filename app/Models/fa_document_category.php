<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fa_document_category extends Model
{
    use HasFactory;

    protected $table = 'fa_document_categories';
    protected $fillable = ['document_code', 'document_category'];

    public function uploadedDocuments()
    {
        return $this->hasMany(fa_uploaded_documents::class, 'document_category', 'document_code');
    }
}
 