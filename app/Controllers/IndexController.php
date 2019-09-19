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
        $limitMonths = 20;

        $jobs = Job::where('visible',1)
            ->get();
        $projects = Project::all();

//        $jobs = array_filter($jobs->toArray(),function (array $job) use ($limitMonths) {
//            return $job['months'] <= $limitMonths;
//        });

        return $this->renderHTML('index.twig',[
            'name' => $name,
            'jobs' => $jobs,
            'projects' => $projects,
        ]);
    }
}