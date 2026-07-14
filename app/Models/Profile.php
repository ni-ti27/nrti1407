<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = [
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
    ];

    /**
     * Relationships
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function taluka()
    {
        return $this->belongsTo(Taluka::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }
}
