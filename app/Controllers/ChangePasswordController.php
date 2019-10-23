<?php

namespace App\Controllers;

use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

class ChangePasswordController extends BaseController
{
    public function index()
    {
        return $this->renderHTML('users/changePassword.twig');
    }

    public function change(ServerRequest $request, ResponseInterface $response): ResponseInterface
    {
        $postData = $request->getParsedBody();
        
        $validator = Validator::key('new_password',Validator::notEmpty()->stringType()->length(8,50));

        try {
            $validator->assert($postData);
            
            $user = User::findOrFail($_SESSION['userId']);

            if ($user->checkPassword($postData['actual_password'])) {
                if ($postData['new_password'] == $postData['confirm_password']) {
                    $user->password = $postData['new_password'];

                    $user->update();

                    $responseMessage = 'Saved';
                } else {
                    $responseMessage = 'Passwords do not match';
                }
            } else {
                $responseMessage = 'Incorrect password';
            }
        } catch (NestedValidationException $e) {
            
            $responseMessage = implode(" - ",$e->findMessages([
                'stringType' => '{{name}} must contain a-z characters and/or simbols',
                'notEmpty' => "{{name}} can't be empty",
                'length' => '{{name}} must contain between 8 and 50 characters',
            ]));
        } catch (ModelNotFoundException $e){
            $responseMessage = 'User not found';
        }

        return $this->renderHTML('users/changePassword.twig',[ 'responseMessage' => $responseMessage ]);
    }
}