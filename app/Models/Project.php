<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

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