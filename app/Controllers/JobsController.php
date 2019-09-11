<?php

namespace App\Controllers;

use App\Models\Job;
use App\Controllers\BaseController;
use Respect\Validation\Validator;

class JobsController extends BaseController {

    public function getAddJobAction($request) {
        $responseMessage = null;

        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();

            $jobValidator = Validator::key('title',Validator::stringType()->notEmpty())
                ->key('description',Validator::stringType()->notEmpty());

            try {
                $jobValidator->assert($postData);

                $job = new Job;
                $job->title = $postData['title'];
                $job->description = $postData['description'];
                $job->visible = 1;
                $job->months = 0;
                $job->id_user = $_SESSION['userId'];

                $file = $request->getUploadedFiles();
                $logo = $file['logo'];

                if ($logo->getError() == UPLOAD_ERR_OK) {
                    $logo->moveTo($job->image);
                }

                $job->save();

                $responseMessage = 'Saved';
            } catch (\Exception $e) {
                $responseMessage = $e->getMessage();
            }
        }

        return $this->renderHTML('addJob.twig', [
            'responseMessage' => $responseMessage
        ]);
    }

    public function getListJobAction($request) {
        if ($request->getMethod() == 'GET') {
            $postData = $request->getParsedBody();

            $jobs = Job::where('id_user',$_SESSION['userId'])->get();
        }

        return $this->renderHTML('listJobs.twig', [
            'jobs' => $jobs,
        ]);
    }
}