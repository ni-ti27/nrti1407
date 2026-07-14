<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sa_application_history extends Model
{
    use HasFactory;
    protected $table = 'sa_application_history';
    protected $fillable = ['sa_reg_no', 'changes_id', 'sa_action_details_id', 'current_dept_id', 'history_field',];

    public function sa_submit_req()
    {
        return $this->belongsTo(sa_submit_req::class, 'sa_reg_no', 'sa_reg_no');
    }

    public function changeInfo()
    {
        return $this->belongsTo(Actions::class, 'changes_id', 'id');
    }

    public function saActions()
    {
        return $this->belongsTo(sa_action_details::class, 'sa_action_details_id', 'id');
    }
}
