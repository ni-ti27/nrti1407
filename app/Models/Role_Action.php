<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_Action extends Model
{
    use HasFactory;

    protected $table = 'role_actions';
    protected $fillable = ['role_id', 'action_id'];
    public function actions()
    {
        return $this->belongsToMany(Actions::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function action()
    {
        return $this->belongsTo(Actions::class, 'action_id', 'id');
    }
}
