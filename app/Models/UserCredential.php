<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class UserCredential extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;

    use HasFactory;
    protected $table = 'user_credentials';

    protected $fillable = [
        'user_id',
        'username',
        'password',
    ];

    public static function generateUsername()
    {
        // Logic to generate a username, for example:
        return 'user_' . uniqid();
    }

    public static function generatePassword()
    {
        // Logic to generate a password, for example:
        return bcrypt('test123'); // You might want a more sophisticated password generation logic
    }

    public function roles() {
        return $this->belongsToMany(Role::class, 'dept_officers_master');
    }
    public function deptOfficersMaster()
    {
        return $this->belongsTo(dept_officers_master::class, 'user_id', 'id');
    }
}
