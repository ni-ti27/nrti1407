<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_Details extends Model
{
    use HasFactory;
    protected $table = 'payment__details';

    protected $fillable = [
        'trans_dt',
        'applicant_id',
        'reg_no',
        'echallan_no',
        'total_amt',
        'demand_id',
        'service_code',
        'party_ref_no',
        'party',
        'paystat_response_xml',
        'bank_recv_dt',
        'bank_recv_tm',
        'sbi_ref',
        'bank_ref_no',
        'bank_rcvd_date',
        'posted_status',
        'posted_date',
        'status',
        'payment_verified',
        'payment_verified_date',
        'updated_by',
    ];
}
