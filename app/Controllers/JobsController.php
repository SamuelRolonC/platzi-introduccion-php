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
            print_r(\Illuminate\Database\Connection::enableQueryLog() );

            try {
                echo $job->save();
            } catch (PDOException $Exception) {
                echo 'error';//$Exception;
            }
        }

        include '../views/addJob.php';
    }
}