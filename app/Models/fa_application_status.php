<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fa_application_status extends Model
{
    use HasFactory;

    protected $table = 'fa_application_statuses';
    protected $fillable = ['status_id', 'action_id', 'fa_reg_no', 'reg_no', 'assigned_off1', 'assigned_off2', 'updated_by'];

    public function requests()
    {
        return $this->hasMany(fa_submit_request::class, 'fa_reg_no', 'fa_reg_no');
    }

    public function statusCheck()
    {
        return $this->hasMany(application_status::class,'id','status_id');
    }

    public function actionCheck()
    {
        return $this->hasMany(Actions::class,'id','action_id');
    }

    public function fa_submit_request()
    {
        return $this->belongsTo(fa_submit_request::class, 'fa_reg_no', 'fa_reg_no');
    }
}
