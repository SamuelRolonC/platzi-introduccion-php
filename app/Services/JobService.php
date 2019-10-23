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
            ->key('city',Validator::optional(Validator::stringType()))
            ->key('description',Validator::optional(Validator::stringType()))
            ->key('started_at',Validator::date())
            ->key('finished_at',Validator::optional(Validator::date()));

        try {  
            $jobValidator->assert($jobData);

            $finishedIsEmpty = empty($jobData['finished_at']);

            if (!(!$finishedIsEmpty xor isset($jobData['working']))) {
                return 'One field, Finished or Working now, must be set';
            }

            if ($finishedIsEmpty) {
                $jobData['finished_at'] = null;
            }

            if ($jobData['image']->getSize() > 0) {
                switch ($jobData['image']->getClientMediaType()) {
                    case 'image/png':
                    case 'image/jpeg':
                    case 'image/gif':
                        break;
                    default:
                        return  'Image must be a png, jpg or gif image file';
                }
            }

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
            $job->working = isset($bodyParams['working']);
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
                'stringType' => '{{name}} must contain a-z characters and/or simbols',
                'notEmpty' => "{{name}} can't be empty",
                'date' => '{{name}} must be a valid date'
            ]));
        }

        return 'Saved';
    }

    public function delete($id)
    {
        $job = Job::findOrFail($id);
        return $job->delete();
    }
}