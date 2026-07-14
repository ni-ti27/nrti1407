<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaActionDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'fa_reg_no',
        'changes',
        'history_field',
        'fa_application_id',
    ];

    public function fa_submit_request()
    {
        return $this->belongsTo(fa_submit_request::class, 'fa_reg_no', 'fa_reg_no');
    }

}
