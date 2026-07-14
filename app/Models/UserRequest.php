<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
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
        'document',
        'registration_no',
        'payment_details',
    ];
}
