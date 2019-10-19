<?php

namespace App\Models;

use App\Traits\HasDefaultImage;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasDefaultImage;

    protected $primaryKey = 'id_information';
}
