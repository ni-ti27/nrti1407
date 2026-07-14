<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class application_status extends Model
{
    use HasFactory;
    protected $table = 'application_status';
    public $fillable = [
       'status',
       'abbreviation',
    ];

    public function action_details()
    {
        return $this->hasMany(action_details::class, 'status_id', 'id');
    }

    public function fa_application_status()
    {
        return $this->hasMany(fa_application_status::class, 'status_id', 'id');
    }
}
