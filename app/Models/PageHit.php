<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageHit extends Model
{
    use HasFactory;

    // Define which attributes are mass assignable
    protected $fillable = ['ip_address', 'visit_date'];

    // Optional: Disable timestamps if you don't want automatic created_at and updated_at handling
    public $timestamps = false;
}
