<?php

namespace App\Services;

use App\Models\Information;

class InformationService
{
    public function storeAndUpdate(array $informationData,$isUpdate = false)
    {
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

        return 'Successful';
    }
}