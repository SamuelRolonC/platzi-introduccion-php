<?php

namespace App\Controllers;

use App\Models\Information;
use App\Services\InformationService;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

class InformationController extends BaseController
{
    protected $informationService;

    public function __construct(InformationService $informationService)
    {
        parent::__construct();
        $this->informationService = $informationService;
    }

    public function index(ServerRequest $request)
    {
        if ($request->getMethod() == 'GET') {
            $information = Information::where('id_user','=',$_SESSION['userId'])->get();
        }

        return $this->renderHTML('/information/index.twig',compact('information'));
    }

    public function store(ServerRequest $request)
    {
        $reponseMessage = '';

        if ($request->getMethod() == 'POST') {
            $bodyParams = $request->getParsedBody();

            $reponseMessage = $this->informationService->storeAndUpdate($bodyParams);
        }
        
        return $this->renderHTML('information/store.twig', [ 'responseMessage' => $reponseMessage ]);
    }

    public function delete(ServerRequest $request)
    {
        $params = $request->getQueryParams();
        
        $validator = Validator::key('id',Validator::intVal());

        try{
            $validator->assert($params);

            $information = Information::find($params['id']);
            
            $information->delete();
        } catch (NestedValidationException $e) {
            $responseMessage = 'Not found';
        }

        return new RedirectResponse('/information');
    }

    public function edit(ServerRequest $request)
    {
        $params = $request->getQueryParams();
        
        $validator = Validator::key('id',Validator::intVal());

        try{
            $validator->assert($params);

            $information = Information::find($params['id']);
            
            $information->delete();
        } catch (NestedValidationException $e) {
            $responseMessage = 'Not found';
        }

        return $this->renderHTML('/information/edit.twig',compact('responseMessage'));
    }

    public function update(ServerRequest $request)
    {
        $reponseMessage = '';
        
        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $postData = array_merge($postData,$request->getQueryParams());

            $reponseMessage = $this->informationService->storeAndUpdate($postData,true);
        }
        
        return $this->renderHTML('information/edit.twig', [ 'responseMessage' => $reponseMessage ]);
    }
}