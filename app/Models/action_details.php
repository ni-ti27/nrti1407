<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class action_details extends Model
{
    use HasFactory;
    protected $table = 'action_details';
    public $fillable = [
        'reg_no',
        'status_id',
        'assigned_off1',
        'assigned_off2',
        'updated_by',
        'ip_address',
        'mac_address',
    ];

    public function user_credentials()
    {
        return $this->hasMany(UserCredential::class, 'assigned_off1','id');
    }

    public function assigned_off1()
    {
        return $this->belongsTo(dept_officers_master::class, 'assigned_off1', 'id');
    }

    public function assigned_off2()
    {
        return $this->belongsTo(dept_officers_master::class, 'assigned_off2', 'id');
    }

    public function status_id()
    {
        return $this->belongsTo(application_status::class, 'status_id', 'id');
    }

    public function submit_req()
    {
        return $this->belongsTo(SubmitRequest::class, 'reg_no', 'reg_no');
    }
}
