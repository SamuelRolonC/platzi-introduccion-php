<?php

namespace App\Controllers;

use App\Models\Job;
use App\Services\JobService;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

class JobsController extends BaseController
{
    private $jobService;

    public function __construct(JobService $jobService)
    {
        parent::__construct();
        $this->jobService = $jobService;
    }

    public function store(ServerRequest $request)
    {
        $responseMessage = '';

        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $postData = array_merge($postData,$request->getUploadedFiles());

            $responseMessage = $this->jobService->storeAndUpdate($postData);
        }

        return $this->renderHTML('jobs/store.twig', [ 'responseMessage' => $responseMessage ]);
    }

    public function index(ServerRequest $request)
    {
        if ($request->getMethod() == 'GET') {
            $jobs = Job::where('id_user',$_SESSION['userId'])->get();
        }

        return $this->renderHTML('jobs/index.twig', compact('jobs'));
    }

    public function delete(ServerRequest $request)
    {
        $params = $request->getQueryParams();

        if (Validator::intVal()->validate($params['id'])) {
            return new RedirectResponse('/jobs');
        }

        $this->jobService->delete($params['id']);

        return new RedirectResponse('/jobs');
    }

    public function edit(ServerRequest $request)
    {
        if ($request->getMethod() == 'GET') {
            $params = $request->getQueryParams();

            if (Validator::intVal()->validate($params['id'])) {
                return new RedirectResponse('/jobs');
            }

            $job = Job::find($params['id']);
        }

        return $this->renderHTML('jobs/edit.twig', compact('job'));
    }

    public function update(ServerRequest $request)
    {
        $responseMessage = '';

        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $postData = array_merge($postData,$request->getUploadedFiles());

            $responseMessage = $this->jobService->storeAndUpdate($postData,true);
        }

        return $this->renderHTML('jobs/edit.twig', [ 'responseMessage' => $responseMessage ]);
    }
}