<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SA_Notice extends Model
{
    use HasFactory;
    protected $table = "sa_notice";

    protected $fillable = [ 'file_path','sa_reg_no', 'file_name', 'status', 'sent_to', 'created_by'];
}
