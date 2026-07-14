<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Additional_Payment_details extends Model
{
    use HasFactory;
    protected $table = 'additional__payment_details';
    protected $fillable = ['reg_no', 'mode_of_info_supply', 'payment_amount', 'reason'];

    public function submitRequest()
    {
        return $this->belongsTo(SubmitRequest::class, 'reg_no', 'reg_no');
    }
}
