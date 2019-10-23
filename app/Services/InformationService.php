<?php

namespace App\Services;

use App\Models\Information;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

class InformationService
{
    public function storeAndUpdate(array $informationData,$isUpdate = false)
    {
        $validator = Validator::key('label',Validator::stringType()->length(1,255))
            ->key('value',Validator::stringType()->length(1,255))
            ->key('link',Validator::optional(Validator::stringType()));

        try {
            $validator->assert($informationData);

            if ($isUpdate) {
                $information = Information::find($informationData['id']);
            } else {
                $information = new Information();
            }
                
            $information->label = $informationData['label'];
            $information->value = $informationData['value'];
            $information->link = isset($informationData['link']);
            $information->id_user = $_SESSION['userId'];
    
            $information->save();
        } catch (NestedValidationException $e) {
            $responseMessage = implode(" - ",$e->findMessages([
                'stringType' => '{{name}} must contain a-z characters and/or simbols',
                'length' => '{{name}} must be 2-20 length'
            ]));
        }

        return 'Successful';
    }
}