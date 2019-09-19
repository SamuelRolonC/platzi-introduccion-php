<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\SoftDeletes;

trait HasDefaultImage
{
    public function getImage($alternativaText)
    {
        if (!$this->image) {
            return "https://ui-avatars.com/api/?name=$alternativaText&size=180";
        }

        return $this->image;
    }
}