<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'role';
    protected $fillable = ['name', 'meta_data'];

    public function dept_officers_master()
    {
        return $this->hasMany(dept_officers_master::class, 'role', 'id');
    }

    public function roleActions()
    {
        return $this->hasMany(Role_Action::class, 'role_id', 'id');
    }
}
