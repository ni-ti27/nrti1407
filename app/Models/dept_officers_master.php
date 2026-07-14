<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class dept_officers_master extends Authenticatable
{
    use HasFactory;
    protected $table = 'dept_officers_master';
    public $fillable = [
        'name',
        'gender',
        'designation',
        'dept_id',
        'dept_code',
        'office_code',
        'suboffice_code',
        'role',
        'is_active',
        'nodel_code',
        'phone_no',
        'mobile_no',
        'email',
        'address1',
        'address2',
        'address3',
        'pincode',
        'state',
        'country',
        'ip_address',
        'mac_address',
        'created_by',
        'username',
        'password',
        'passcode',
        'password_change'
    ];

    public function userCredential()
    {
        return $this->hasOne(UserCredential::class, 'id', 'user_id');
    }

    public function actionDetailsAssignedAsOfficer1()
    {
        return $this->hasMany(action_details::class, 'assigned_off1', 'id');
    }

    public function actionDetailsAssignedAsOfficer2()
    {
        return $this->hasMany(action_details::class, 'assigned_off2', 'id');
    }

    public function role()
    {
        return $this->belongsTo(role::class, 'role', 'id');
    }
}
