<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sa_action_details extends Model
{
    use HasFactory;
    protected $table = 'sa_action_details';
    protected $fillable = ['reg_no', 'status_id', 'assigned_off1', 'assigned_off2', 'updated_by', 'ip_address', 'action_id'];

    public function sa_submit_req()
    {
        return $this->belongsTo(sa_submit_req::class, 'reg_no', 'reg_no');
    }

    public function status_id()
    {
        return $this->belongsTo(application_status::class, 'status_id', 'id');
    }

    public function assigned_off1()
    {
        return $this->belongsTo(dept_officers_master::class, 'assigned_off1', 'id');
    }

    public function assigned_off2()
    {
        return $this->belongsTo(dept_officers_master::class, 'assigned_off2', 'id');
    }
}
