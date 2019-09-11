<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'jobs';

    public function __construct() {
        $length = 5;
        $directory = 'uploads';
        // default to this files directory if empty...
        $dir = !empty($directory) && is_dir($directory) ? $directory : dirname(__FILE__);

        do {
            $key = '';
            $keys = array_merge(range(0, 9), range('a', 'z'));

            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand($keys)];
            }
        } while (file_exists($dir . '/' . $key));

        $this->image = $key;
    }

    public function getDurationAsString()
    {
        $years = floor($this->months / 12);
        $extraMonths = $this->months % 12;

        $duration = '';

        if ($years != 0) {
            $duration .= "$years year";

            if ($years > 1) {
                $duration .= "s";
            }

            if ($extraMonths != 0) {
                $duration .= " and $extraMonths month";

                if ($extraMonths > 1) {
                    $duration .= "s";
                }

                $duration .= ".";

            } else {
                $duration .= ".";
            }
        } else {
            if ($this->months != 0) {
                $duration .= "$this->months month";

                if ($extraMonths > 1) {
                    $duration .= "s";
                }

                $duration .= ".";
            }
        }       

        return $duration;
    }
}