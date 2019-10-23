<?php


namespace App\Controllers;


use App\Models\Message;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

class ContactController extends BaseController
{
    public function index()
    {
        return $this->renderHTML('contact/index.twig');
    }

    public function send(ServerRequest $request)
    {
        $contactData = $request->getParsedBody();

        $validator = Validator::key('name',Validator::alnum()->length(2,20))
            ->key('email',Validator::email())
            ->key('message',Validator::stringType()->notEmpty());

        try {
            $validator->assert($contactData);

            $message = new Message();

            $message->name = $contactData['name'];
            $message->email = $contactData['email'];
            $message->message = $contactData['message'];
            $message->sent = false;
            
            $message->save();
        } catch (NestedValidationException $e) {
            $responseMessage = implode(" - ",$e->findMessages([
                'stringType' => '{{name}} must contain a-z characters and/or simbols',
                'alnum' => '{{name}} must be alphanumeric',
                'notEmpty' => "{{name}} can't be empty",
                'length' => '{{name}} must be 2-20 length',
                'email' => 'Must enter a valid email'          
            ]));
        }

        $responseMessage = 'Successful';

        return $this->renderHTML('contact/index.twig', compact('reponseMessage'));
    }
}