<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    public function setPasswordAttribute($value) 
    {
        $this->attributes['password'] = password_hash($value,PASSWORD_BCRYPT);
    }

    public function checkPassword($password)
    {
        return password_verify($password,$this->attributes['password']);
    }
}