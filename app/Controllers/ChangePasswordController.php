<?php


namespace App\Controllers;


use App\Models\User;
use Zend\Diactoros\ServerRequest;

class ChangePasswordController extends BaseController
{
    public function index()
    {
        return $this->renderHTML('users/changePassword.twig');
    }

    public function change(ServerRequest $request)
    {
        $postData = $request->getParsedBody();

        $user = User::find($_SESSION['userId']);

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

        return $this->renderHTML('users/changePassword.twig',[ 'responseMessage' => $responseMessage ]);
    }
}