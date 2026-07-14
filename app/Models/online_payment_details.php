<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class online_payment_details extends Model
{
    use HasFactory;

    protected $table = 'online_payment_details';

    protected $fillable = [
        'applicant_id',
        'reg_no',
        'echallan_no',
        'total_amt',
        'demand_id',
        'paystat_response_xml',
        'bank_ref_no',
        'bank_rcvd_date',
        'status',
        'payment_verified',
        'payment_verified_date',
        'updated_by',
    ];
}
