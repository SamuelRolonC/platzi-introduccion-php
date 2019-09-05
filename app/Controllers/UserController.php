<?php

namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator;

class UserController extends BaseController
{
    public function getAddUserAction($request) {
        $responseMessage = null;

        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();

            $jobValidator = Validator::key('username',Validator::stringType()->notEmpty())
                ->key('password',Validator::stringType()->notEmpty());
            
            try {
                $jobValidator->assert($postData);

                $user = new User;
                $user->username = $postData['username'];
                $user->password = $postData['password'];

                $user->save();

                $responseMessage = 'Saved';
            } catch (\Exception $e) {
                $responseMessage = $e->getMessage();
            }
        }

        return $this->renderHTML('addUser.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
}