<?php

namespace App\Controllers;

use App\Models\{
    Job, 
    Project, 
    User,
    Information,
    Skill,
};

class IndexController extends BaseController
{
    public function indexAction()
    {
        $user = User::find($_SESSION['userId']);

        $jobs = Job::where('id_user','=',$_SESSION['userId'])->get();
        $projects = Project::where('id_user','=',$_SESSION['userId'])->get();
        $information = Information::where('id_user','=',$_SESSION['userId'])->get();
        $skills = Skill::where('id_user','=',$_SESSION['userId'])->get();

        return $this->renderHTML('index.twig', compact([
            'user',
            'information',
            'jobs',            
            'projects',
            'skills',
        ]));
    }
}