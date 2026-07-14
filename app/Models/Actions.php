<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actions extends Model
{
    use HasFactory;

    protected $table = 'actions';
    protected $fillable = ['name'];

    public function role_action()
    {
        return $this->hasMany(Role_Action::class, 'action_id', 'id');
    }
}
