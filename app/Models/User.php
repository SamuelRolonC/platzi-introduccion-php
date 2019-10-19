<?php

namespace App\Models;

use App\Traits\HasDefaultImage;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasDefaultImage;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $hidden = [
        'password'
    ];

    public function setPasswordAttribute($value) 
    {
        $this->attributes['password'] = password_hash($value,PASSWORD_BCRYPT);
    }

    /**
     * Check if user has a image path.
     * 
     * Image path is not setted at user creation so on the first time 
     * that image is setted is needed to create a path. 
     */
    public function checkImagePath()
    {
        if (!$this->image) {
            $this->image = $this->findAnImagePath();
            $this->save();
        }
    }

    public function checkPassword($password)
    {
        return password_verify($password,$this->attributes['password']);
    }
}