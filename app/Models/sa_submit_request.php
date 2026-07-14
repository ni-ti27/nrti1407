<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sa_submit_request extends Model
{
    use HasFactory;
    protected $table = 'sa_submit_requests';

    protected $fillable = [
        'applied_dept', 'applied_subdept', 'applied_office','dept_id', 'reg_no','fa_reg_no', 'sa_reg_no', 'name', 'gender', 'address_line1', 'address_line2', 'address_line3', 'pincode', 'country', 'state', 'district', 'taluka', 'village', 'phone_no', 'mobile_no', 'email_id', 'online_request','newrequest_date', 'pio_id', 'pio_name', 'pio_address','pio_response', 'faa_id', 'faa_date','faa_name','faa_address', 'faa_response'
    ];

    public function uploadedDocuments()
    {
        return $this->hasMany(fa_uploaded_documents::class, 'sa_reg_no', 'reg_no');
    }

    public function saActionDetails()
    {
        return $this->hasMany(sa_action_details::class, 'reg_no', 'sa_reg_no');
    }

    public function saApplicanteply()
    {
        return $this->hasMany(SA_Reply_To_Applicant::class, 'sa_reg_no', 'sa_reg_no');
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

    public function saDocuments()
    {
        return $this->hasMany(sa_uploaded_documents::class,'sa_reg_no', 'sa_reg_no');
    }

    public function pioResponseData()
    {
        return $this->belongsTo(pioResponse::class,'pio_response', 'id');
    }

    public function faaResponseData()
    {
        return $this->belongsTo(pioResponse::class,'faa_response', 'id');
    }

    public function genRegNumber($depCode,$monthYear,$insertId)
    {
        // FA is First Appeal
        $insertId = str_pad($insertId, 5, '0', STR_PAD_LEFT);
        $registrationNumber = $depCode.'/SA/'.$monthYear.'/'.$insertId;

        return $registrationNumber;
    }
}
