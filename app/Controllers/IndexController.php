<?php

namespace App\Controllers;

use App\Models\{
    Job, 
    Project, 
    User,
    Information,
};

class IndexController extends BaseController
{
    public function indexAction()
    {
        $name = 'Samuel Rolón Cicciari';
        $limitMonths = 20;

        $user = User::find($_SESSION['userId']);

        $jobs = Job::where('id_user','=',$_SESSION['userId'])->get();
        $projects = Project::where('id_user','=',$_SESSION['userId'])->get();
        $information = Information::where('id_user','=',$_SESSION['userId'])->get();

        return $this->renderHTML('index.twig',[
            'user' => $user,
            'information' => $information,
            'jobs' => $jobs,            
            'projects' => $projects,
        ]);
    }
}