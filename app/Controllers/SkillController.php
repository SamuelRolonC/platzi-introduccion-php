<?php

namespace App\Controllers;

use App\Models\Skill;
use App\Services\SkillService;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;

class SkillController extends BaseController
{
    private $skillService;

    public function __construct(SkillService $skillService)
    {
        parent::__construct();
        $this->skillService = $skillService;
    }

    public function index(ServerRequest $request)
    {
        $responseMessage = '';

        if ($request->getMethod() == 'GET') {
            $skills = Skill::where('id_user','=',$_SESSION['userId'])->get();
        }

        return $this->renderHTML('skills/index.twig', compact('skills'));
    }

    public function store(ServerRequest $request)
    {
        $responseMessage = '';

        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            
            
        }

        return $this->renderHTML('skills/store.twig', [
            'responseMessage' => $responseMessage
        ]);
    }

    public function delete(ServerRequest $request)
    {
        $dataRequest = $request->getQueryParams();

        $skill = Skill::find($dataRequest['id']);
        $skill->delete();

        return new RedirectResponse('/skills');
    }

    public function edit(ServerRequest $request)
    {
        $responseMessage = '';

        if ($request->getMethod() == 'GET') {
            $params = $request->getQueryParams();
            $skill = Skill::find($params['id']);
        }

        return $this->renderHTML('skills/edit.twig', compact('skill'));
    }

    public function update(ServerRequest $request)
    {
        $responseMessage = '';

        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $postData = array_merge($postData,$request->getQueryParams());

            $responseMessage = $this->skillService->storeAndUpdate($postData, true);
        }

        return $this->renderHTML('skills/edit.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
}