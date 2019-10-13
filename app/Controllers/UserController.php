<?php

namespace App\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Respect\Validation\Validator;
use Zend\Diactoros\Response\RedirectResponse;
use Respect\Validation\Exceptions\NestedValidationException;
use Zend\Diactoros\ServerRequest;

class UserController extends BaseController
{
    public function getAddUserAction(ServerRequest $request) {
        $responseMessage = '';

        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();

            $jobValidator = Validator::key('username',Validator::notEmpty()->stringType()->length(3,20))
                ->key('password',Validator::notEmpty()->stringType()->length(8,50))
                ->key('name',Validator::notEmpty()->stringType()->length(2,20))
                ->key('lastname',Validator::notEmpty()->stringType()->length(2,20))
                ->key('email',Validator::notEmpty()->stringType()->email())
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
                $responseMessage = $e->findMessages([
                    'stringType' => 'El campo {{name}} debe contener valores alfanuméricos',
                    'notEmpty' => 'El campo {{name}} no puede estar vació',
                    'length' => 'Los campos Nombre y Apellido deben contener entre 2 y 20 caracteres. El campo username debe contener entre 3 y 20  carácteres. El campo contraseña debe contener entre 8 y 50 carácteres',
                    'email' => 'Debes ingresar un email válido',
                    'intVal' => 'El campo teléfono debe ser entero'
                ]);
                $responseMessage = implode(" - ",$responseMessage);
            } catch (QueryException $e) {
                $responseMessage = $e->getMessage();
            }
        }

        return $this->renderHTML('addUser.twig', [
            'responseMessage' => $responseMessage
        ]);
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

            $responseMessage = $user->save();
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
        }

        return $this->renderHTML('users/summary.twig', [ 'responseMessage' => $responseMessage ]);
    }
}