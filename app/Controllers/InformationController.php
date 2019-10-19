<?php

namespace App\Controllers;

use App\Models\Information;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;

class InformationController extends BaseController
{
    public function index(ServerRequest $request)
    {
        $reponseMessage = '';

        if ($request->getMethod() == 'GET') {
            $information = Information::where('id_user','=',$_SESSION['userId'])->get();
        }

        return $this->renderHTML('/',compact('information'));
    }

    public function store(ServerRequest $request)
    {
        $reponseMessage = '';

        if ($request->getMethod() == 'POST') {
            $bodyParams = $request->getParsedBody();

            $information = new Information();
            
            $information->label = $bodyParams['label'];
            $information->value = $bodyParams['value'];
            $information->type = $bodyParams['type'] ?? 'text';
            $information->id_user = $_SESSION['userId'];

            $information->save();

            $reponseMessage = 'Successful';
        }
        
        return $this->renderHTML('information/store.twig', [ 'responseMessage' => $reponseMessage ]);
    }
}