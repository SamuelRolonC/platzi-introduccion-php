<?php

namespace App\Services;

use App\Models\Skill;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

class SkillService
{
    public function storeAndUpdate(array $skillData, $isUpdate = false)
    {
        $validator = Validator::key('title',Validator::stringType()->notEmpty())
            ->key('description',Validator::stringType()->notEmpty())
            ->key('level',Validator::intVal()->notEmpty());

        try {
            $validator->assert($skillData);

            if ($isUpdate) {
                $skill = Skill::find($skillData['id']);
            } else {
                $skill = new Skill();
                $skill->id_user = $_SESSION['userId'];
            }

            $skill->title = $skillData['title'];
            $skill->description = $skillData['description'];
            $skill->level = $skillData['level'];
            
            $skill->save();
        } catch (NestedValidationException $e) {
            return implode(" - ",$e->findMessages([
                'stringType' => '{{name}} must contain a-z characters and/or simbols',
                'notEmpty' => "{{name}} can't be empty",
                'length' => '{{name}} must contain between 8 and 50 characters',
                'date' => '{{name}} must be a valid date'             
            ]));
        }

        return 'Saved';
    }

    public function delete($id)
    {
        $job = Skill::findOrFail($id);
        $job->delete();
    }
}

