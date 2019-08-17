<?php

namespace App\Models;

class BaseElement implements Printable
{
    public $title;
    public $description;
    public $visible = true;
    public $months;

    public function __construct($title = 'N/A',$description = 'N/A')
    {
        $this->setTitle($title);
        $this->description = $description;
    }

    public function setTitle($title)
    {
        if ($title == '') {
            $this->title = 'N/A';
        } else {
            $this->title = $title;
        }
    }

    public function getTitle()
    {
        return $this->title;
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

    public function getDescription()
    {
        return $this->description;
    } 
}