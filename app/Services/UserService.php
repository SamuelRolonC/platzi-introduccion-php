<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

class UserService
{
    public function storeAndUpdate(array $userData, $isUpdate = false)
    {        
        try {
            if ($isUpdate) {
                $user = User::findOrFail($_SESSION['userId']);

                $userValidator = Validator::key('name',Validator::notEmpty()->stringType()->length(2,20))
                    ->key('lastname',Validator::notEmpty()->stringType()->length(2,20))
                    ->key('email',Validator::notEmpty()->stringType()->email())
                    ->key('phone',Validator::optional(Validator::intVal()));
            } else {
                $user = new User;

                $userValidator = Validator::key('username',Validator::notEmpty()->stringType()->length(3,20))
                    ->key('password',Validator::notEmpty()->stringType()->length(8,50))
                    ->key('name',Validator::notEmpty()->stringType()->length(2,20))
                    ->key('lastname',Validator::notEmpty()->stringType()->length(2,20))
                    ->key('email',Validator::notEmpty()->stringType()->email())
                    ->key('phone',Validator::optional(Validator::intVal()));
            }
            
            $userValidator->assert($userData);   

            if (!$isUpdate) {
                $user->username = $userData['username'];
                $user->password = $userData['password'];
                $user->summary = '';
            }
            
            $user->name = $userData['name'];
            $user->lastname = $userData['lastname'];
            $user->email = $userData['email'];
            
            if (filter_var($userData['phone'], FILTER_VALIDATE_INT) === false ) {
                $user->phone = null;
            } else {
                $user->email = $userData['phone'];
            }

            $user->save();
        } catch (NestedValidationException $e) {
            $responseMessage = $e->findMessages([
                'stringType' => 'El campo {{name}} debe contener valores alfanuméricos',
                'notEmpty' => 'El campo {{name}} no puede estar vació',
                'length' => 'Los campos Nombre y Apellido deben contener entre 2 y 20 caracteres. El campo username debe contener entre 3 y 20  carácteres. El campo contraseña debe contener entre 8 y 50 carácteres',
                'email' => 'Debes ingresar un email válido',
                'intVal' => 'El campo teléfono debe ser entero'
            ]);
            return implode(" - ",$responseMessage);
        } catch (QueryException $e) {
            return $e->getMessage();
        }

        return 'Successful';
    }
}