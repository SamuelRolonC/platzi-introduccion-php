<?php

namespace App\Controllers;

use Exception;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;

class UserController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    public function store(ServerRequest $request) {
        $responseMessage = '';

        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();

            $responseMessage = $this->userService->storeAndUpdate($postData);
        }

        return $this->renderHTML('users/store.twig', [ 'responseMessage' => $responseMessage ]);
    }

    public function getSummary(ServerRequest $request)
    {
        $summary = '';

        if ($request->getMethod() == 'GET') {
            try {
                $user = User::findOrFail($_SESSION['userId']);
                $summary = $user->summary;
            } catch (Exception $e) {
                $summary = $e->getMessage();
            }
        }

        return $this->renderHTML('users/summary.twig', [ 'summary' => $summary ]);
    }

    public function setSummary(ServerRequest $request)
    {
        $params = $request->getParsedBody();

        try {
            $user = User::findOrFail($_SESSION['userId']);
            $user->summary = $params['summary'];

            $user->save();

            $responseMessage = 'Successful';
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
        }

        return $this->renderHTML('users/summary.twig', [ 'responseMessage' => $responseMessage ]);
    }

    public function setPhoto(ServerRequest $request)
    {
        if ($request->getMethod() == 'POST') {
            $photoFile = $request->getUploadedFiles()['photo'];
    
            if ($photoFile->getError() == UPLOAD_ERR_OK && $_SESSION['userId']) {
                try {
                    $user = User::find($_SESSION['userId']);
                    $user->checkImagePath();
    
                    $photoFile->moveTo($user->image);
    
                    $responseMessage = 'Saved';
                } catch (\Exception $e) {
                    $responseMessage = $e->getMessage();
                }
            }
        }        

        return $this->renderHTML('users/photo.twig', [ 'responseMessage' => $responseMessage ]);
    }

    public function getPhoto()
    {
        $user = User::find($_SESSION['userId']);

        $user->only(['image']);

        return $this->renderHTML('users/photo.twig', compact('user'));
    }

    public function edit(ServerRequest $request)
    {
        $user = '';

        if ($request->getMethod() == 'GET') {
            $user = User::find($_SESSION['userId']);

            $user = $user->only([
                'username',
                'name',
                'lastname',
                'email',
                'phone'
            ]);
        }
        
        return $this->renderHTML('users/edit.twig', [ 'user' => $user ]);
    }

    public function update(ServerRequest $request)
    {
        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();

            $responseMessage = $this->userService->storeAndUpdate($postData, true);
        }
        
        return $this->renderHTML('users/edit.twig', [ 'responseMessage' => $responseMessage ]);
    }
}