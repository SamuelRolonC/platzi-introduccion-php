<?php

namespace App\Models;

use App\Traits\HasDefaultImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasDefaultImage;
    use SoftDeletes;

    protected $table = 'jobs';
    protected $primaryKey = 'id_job';

    public function __construct()
    {
        $this->image = $this->findAnImagePath();
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