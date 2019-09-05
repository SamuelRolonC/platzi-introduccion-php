<?php

namespace App\Controllers;

use App\Models\{
    Job,
    Project
};

class IndexController extends BaseController
{
    public function indexAction()
    {
        $name = 'Samuel Rolón Cicciari';
        $limitMonths = 1000;

        $jobs = Job::all();
        $projects = Project::all();

        return $this->renderHTML('index.twig',[
            'name' => $name,
            'jobs' => $jobs,
        ]);
    }
}