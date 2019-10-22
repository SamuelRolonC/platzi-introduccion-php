<?php


namespace App\Controllers;


use App\Models\Project;
use App\Services\ProjectService;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;

class ProjectController extends BaseController
{
    private $projectService;

    public function __construct(ProjectService $projectService)
    {
        parent::__construct();
        $this->projectService = $projectService;
    }

    public function store(ServerRequest $request)
    {
        $responseMessage = null;

        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $postData['imageFile'] = $request->getUploadedFiles()['logo'];

            $responseMessage = $this->projectService->create($postData);
        }

        return $this->renderHTML('projects/store.twig', [
            'responseMessage' => $responseMessage
        ]);
    }

    public function index(ServerRequest $request)
    {
        if ($request->getMethod() == 'GET') {
            $projects = Project::where('id_user','=',$_SESSION['userId'])->get();
        }

        return $this->renderHTML('projects/index.twig', compact('projects'));
    }

    public function delete(ServerRequest $request)
    {
        $dataRequest = $request->getQueryParams();
        $this->projectService->delete($dataRequest['id']);

        return new RedirectResponse('/projects');
    }

    public function edit(ServerRequest $request)
    {
        $responseMessage = '';

        if ($request->getMethod() == 'GET') {
            $params = $request->getQueryParams();
            $project = Project::find($params['id']);
        }

        return $this->renderHTML('projects/edit.twig', compact('project'));
    }

    public function update(ServerRequest $request)
    {
        $responseMessage = '';

        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $postData = array_merge($postData, $request->getUploadedFiles(),$request->getQueryParams());

            $responseMessage = $this->projectService->storeAndUpdate($postData, true);
        }

        return $this->renderHTML('projects/edit.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
}