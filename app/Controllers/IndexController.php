<?php

namespace App\Controllers;

use App\Models\{
    Job,
    Project
};

class IndexController 
{
    public function indexAction()
    {
        $name = 'Samuel Rolón Cicciari';
        $limitMonths = 1000;

        $jobs = Job::all();
        $projects = Project::all();

        include '../views/index.php';
    }
}