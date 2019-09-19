<?php

namespace App\Controllers;

use App\Models\Job;
use App\Services\JobService;
use Respect\Validation\Validator;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;

class JobsController extends BaseController
{
    private $jobService;

    public function __construct(JobService $jobService)
    {
        parent::__construct();
        $this->jobService = $jobService;
    }

    public function getAddJobAction(ServerRequest $request)
    {
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

    public function indexAction(ServerRequest $request) {
        if ($request->getMethod() == 'GET') {
            $jobs = Job::where('id_user',$_SESSION['userId'])
                ->whereNull('deleted_at')
                ->get();
        }

        return $this->renderHTML('jobs/index.twig', compact('jobs'));
    }

    public function deleteAction(ServerRequest $request) {
        $params = $request->getQueryParams();
        $this->jobService->delete($params['id']);

        return new RedirectResponse('/jobs');
    }
}