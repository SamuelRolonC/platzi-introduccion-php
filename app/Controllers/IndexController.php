<?php

namespace App\Controllers;

use App\Models\{Job, Project, User};

class IndexController extends BaseController
{
    public function indexAction()
    {
        $name = 'Samuel Rolón Cicciari';
        $limitMonths = 20;

        $jobs = Job::where('visible',1)
            ->get();
        $projects = Project::all();

        $user = User::find($_SESSION['userId']);

        return $this->renderHTML('index.twig',[
            'user' => $user,
            'jobs' => $jobs,
            'projects' => $projects,
        ]);
    }
}