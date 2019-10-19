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

    public function findAnImagePath()
    {
        $length = 5;
        $directory = 'uploads/';
        // default to this files directory if empty...
        $dir = !empty($directory) && is_dir($directory) ? $directory : dirname(__FILE__);

        do {
            $key = '';
            $keys = array_merge(range(0, 9), range('a', 'z'));

            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand($keys)];
            }
        } while (file_exists($dir . '/' . $key));

        return $directory.$key;
    }
}