<?php

namespace App\Services;

use App\Models\Job;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

class JobService
{
    public function storeAndUpdate(array $jobData, $isUpdate = false)
    {
        $jobValidator = Validator::key('title',Validator::stringType()->notEmpty())
            ->key('company',Validator::stringType()->notEmpty())
            ->key('city',Validator::stringType()->notEmpty())
            ->key('description',Validator::stringType()->notEmpty())
            ->key('started_at',Validator::date()->notEmpty())
            ->key('finished_at',Validator::date()->notEmpty())
            ->key('working',Validator::optional(Validator::stringType()->notEmpty()));

        try {
            $jobValidator->assert($jobData);

            if ($isUpdate) {
                $job = Job::find($jobData['id']);
            } else {
                $job = new Job;
            }

            $job->title = $jobData['title'];
            $job->company = $jobData['company'];
            $job->city = $jobData['city'];
            $job->description = $jobData['description'];
            $job->started_at = $jobData['started_at'];
            $job->finished_at = $jobData['finished_at'];
            $job->working = isset($bodyParams['working']);;
            $job->id_user = $_SESSION['userId'];
            
            if (isset($jobData['image'])) {
                $imageFile = $jobData['image'];

                if ($imageFile->getError() == UPLOAD_ERR_OK) {
                $imageFile->moveTo($job->image);
            }
            }

            $job->save();
        } catch (NestedValidationException $e) {
            return implode(" - ",$e->findMessages([
                'stringType' => '{{name}} must be alfanumeric',
                'notEmpty' => "{{name}} can't be empty",
                'length' => '{{name}} must contain between 8 and 50 characters',
                'date' => '{{name}} must be a valid date'             
            ]));
        }

        return 'Saved';
    }

    public function delete($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();
    }
}