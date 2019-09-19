<?php

namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;

class AuthController extends BaseController
{
    public function getLogin() {
        return $this->renderHTML('login.twig');
    }

    public function postLogin(ServerRequest $request) {
        $postData = $request->getParsedBody();

        $user = User::where('username',$postData['username'])->first();
        
        if ($user) {
            if ($user->checkPassword($postData['password'])) {
                $_SESSION['userId'] = $user->id_user;
                return new RedirectResponse('/admin');
            } else {
                $responseMessage = 'Unauthorized';
            }
        } else {
            $responseMessage = 'Unauthorized';
        }

        return $this->renderHTML('login.twig', [
            'responseMessage' => $responseMessage
        ]);
    }

    public function getLogout() {
        unset($_SESSION['userId']);
        return new RedirectResponse('/login');
    }
}