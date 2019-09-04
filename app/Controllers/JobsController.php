<?php

namespace App\Controllers;

use App\Models\Job;

class JobsController {
    public function getAddJobAction($request) {
        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $job = new Job;
            $job->title = $postData['title'];
            $job->description = $postData['description'];
            $job->visible = 1;
            $job->months = 0;

            $job->save();
        }

        include '../views/addJob.php';
    }
}