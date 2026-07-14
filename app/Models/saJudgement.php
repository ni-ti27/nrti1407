<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class saJudgement extends Model
{
    use HasFactory;

    protected $table = 'sa_dispose';

    protected $fillable = ['sa_reg_no', 'disp_reason', 'disp_file_name', 'disp_file_path', 'disp_file_original_name', 'disp_file2_name', 'disp_file2_path', 'disp_file2_original_name', 'disp_file2_text', 'disp_file3_name', 'disp_file3_path', 'disp_file3_original_name', 'disp_file3_text', 'meta_data'];

    public function saSubmitRequest()
    {
        return $this->belongsTo(sa_submit_request::class, 'sa_reg_no', 'sa_reg_no');
    }
}
