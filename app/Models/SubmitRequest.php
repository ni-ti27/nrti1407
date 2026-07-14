<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmitRequest extends Model
{
    use HasFactory;

    public $fillable = [
        'applied_dept',
        'applied_subdept',
        'applied_office',
        'name',
        'gender',
        'address_line1',
        'address_line2',
        'address_line3',
        'pincode',
        'country',
        'state',
        'district',
        'taluka',
        'village',
        'phone_no',
        'mobile_no',
        'email_id',
        'edu_status',
        'qualification',
        'origin_country',
        'is_bpl',
        'card_no',
        'issued_year',
        'issuing_authority',
        'request_text',
        'reg_no',
        'payment_details',
        'document_name',
        'document_path',
        'document_name_original',
        'dept_id',
    ];

    public function state() {
        return $this->belongsTo(State::class, 'state', 'id');
    }

    public function village() {
        return $this->belongsTo(Village::class, 'village', 'id');
    }

    public function district() {
        return $this->belongsTo(District::class, 'district', 'id');
    }

    public function actionDetails()
    {
        return $this->hasMany(action_details::class, 'reg_no', 'reg_no');
    }

    public function genRegNumber($depCode,$monthYear,$insertId)
    {
        // NR is new request
        $insertId = str_pad($insertId, 5, '0', STR_PAD_LEFT);
        $registrationNumber = $depCode.'/NR/'.$monthYear.'/'.$insertId;

        return $registrationNumber;
    }

    public function additionalPaymentDetails()
    {
        return $this->hasOne(Additional_Payment_details::class, 'reg_no', 'reg_no');
    }

    public function reqToApplicant()
    {
        return $this->hasOne(Req_to_applicant::class, 'reg_no', 'reg_no');
    }

    public function reqDisposeOf()
    {
        return $this->hasOne(Req_dispose_of::class, 'reg_no', 'reg_no');
    }

    /**
     * _createDeptCode function
     *
     * @param [string] $deptId
     * @param [string] $officeId
     * @param [string] $subofficeId
     * @return string
     */
    public function createDeptCode($deptId,$subDeptId,$officeDeptId)
    {
        $dept = ($deptId < 10) ? '0' . $deptId : $deptId;
        $office = ($subDeptId < 10) ? '0' . $subDeptId : $subDeptId;
        $subOffice = ($officeDeptId < 10) ? '0' . $officeDeptId : $officeDeptId;

        $dcode = $dept.''.$office.''.$subOffice;
        return $dcode;
    }

}
