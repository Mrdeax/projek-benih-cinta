<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'username', 'email', 'password', 'role', 'phone', 'address'];
    protected $hidden = ['password', 'remember_token'];

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOfficer()
    {
        return $this->role === 'petugas';
    }

    public function isMember()
    {
        return $this->role === 'member';
    }
}