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

                $userValidator = Validator::key('name',Validator::notEmpty()->alnum()->length(2,20))
                    ->key('lastname',Validator::notEmpty()->alnum()->length(2,20))
                    ->key('email',Validator::notEmpty()->email())
                    ->key('phone',Validator::optional(Validator::intVal()));
            } else {
                $user = new User;

                $userValidator = Validator::key('username',Validator::notEmpty()->alnum()->noWhitespace()->length(3,20))
                    ->key('password',Validator::notEmpty()->stringType()->length(8,50))
                    ->key('name',Validator::notEmpty()->alnum()->length(2,20))
                    ->key('lastname',Validator::notEmpty()->alnum()->length(2,20))
                    ->key('email',Validator::notEmpty()->email())
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
                'stringType' => '{{name}} must contain a-z characters and/or simbols',
                'alnum' => '{{name}} must be alphanumeric',
                'notEmpty' => "{{name}} can't be empty",
                'length' => 'Name and last name must be 2-20 length, username must be 3-20 length and password must be 8-50 length',
                'email' => 'Must enter a valid email',
                'intVal' => '{{name}} must be integer'
            ]);
            return implode(" - ",$responseMessage);
        } catch (QueryException $e) {
            return $e->getMessage();
        }

        return 'Successful';
    }
}