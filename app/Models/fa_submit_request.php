<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fa_submit_request extends Model
{
    use HasFactory;
    protected $table = 'fa_submit_requests';

    protected $fillable = [
        'applied_dept', 'applied_subdept', 'applied_office', 'dept_id', 'reg_no', 'name', 'gender', 'address_line1', 'address_line2', 'address_line3', 'pincode', 'country', 'state', 'district', 'taluka', 'village', 'phone_no', 'mobile_no', 'email_id', 'newrequest_date', 'pioname', 'pioaddr', 'pioresponse', 'firstappeal_Subdate', 'status_id', 'fa_reg_no','requested_note'
    ];

    public function uploadedDocuments()
    {
        return $this->hasMany(fa_uploaded_documents::class, 'reg_no', 'reg_no');
    }

    public function faRegistration()
    {
        return $this->hasOne(fa_application_status::class, 'fa_reg_no', 'fa_reg_no');
    }

    public function state() {
        return $this->belongsTo(State::class, 'state', 'id');
    }

    public function village() {
        return $this->belongsTo(Village::class, 'village', 'id');
    }

    public function district() {
        return $this->belongsTo(District::class, 'district', 'id');
    }

    public function FaActions()
    {
        return $this->belongsTo(FaActions::class, 'fa_reg_no', 'fa_reg_no');
    }

    public function FaActionDetails()
    {
        return $this->belongsTo(FaActionDetails::class, 'fa_reg_no', 'fa_reg_no');
    }

    public function genRegNumber($depCode,$monthYear,$insertId)
    {
        // FA is First Appeal
        $insertId = str_pad($insertId, 5, '0', STR_PAD_LEFT);
        $registrationNumber = $depCode.'/FA/'.$monthYear.'/'.$insertId;

        return $registrationNumber;
    }

    public function pioResponseData()
    {
        return $this->belongsTo(pioResponse::class,'pioresponse', 'id');
    }
}
