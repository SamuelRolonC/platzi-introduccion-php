<?php

namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator;
use Zend\Diactoros\Response\RedirectResponse;
use Respect\Validation\Exceptions\NestedValidationException;

class UserController extends BaseController
{
    public function getAddUserAction($request) {
        $responseMessage = null;

        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();

            $jobValidator = Validator::key('username',Validator::stringType()->notEmpty())
                ->key('password',Validator::stringType()->notEmpty())
                ->key('name',Validator::stringType()->notEmpty())
                ->key('lastname',Validator::stringType()->notEmpty())
                ->key('email',Validator::stringType()->notEmpty())
                ->key('phone',Validator::intVal());
            
            try {
                $jobValidator->assert($postData);

                $user = new User;
                $user->username = $postData['username'];
                $user->password = $postData['password'];
                $user->name = $postData['name'];
                $user->lastname = $postData['lastname'];
                $user->email = $postData['email'];
                $user->phone = $postData['phone'];
                $user->summary = '';

                $user->save();

                return new RedirectResponse('/login');
            } catch (NestedValidationException $e) {
                $responseMessage = $e->getFullMessage();
            }
        }

        return $this->renderHTML('addUser.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
}