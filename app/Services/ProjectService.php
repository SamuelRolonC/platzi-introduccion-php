<?php


namespace App\Services;


use App\Models\Project;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Zend\Diactoros\Exception\UploadedFileAlreadyMovedException;
use Zend\Diactoros\Exception\UploadedFileErrorException;

class ProjectService
{
    public function storeAndUpdate(array $projectData, $isUpdate = false)
    {
        $projectValidator = Validator::key('title',Validator::stringType()->notEmpty())
            ->key('description',Validator::stringType()->notEmpty());

        try {
            $projectValidator->assert($projectData);

            if ($isUpdate) {
                $project = Project::find($projectData['id']);
            } else {
                $project = new Project;
                $project->id_user = $_SESSION['userId'];
            }

            $project->title = $projectData['title'];
            $project->description = $projectData['description'];

            if (isset($projectData['image'])) {
                $imageFile = $projectData['image'];

                if ($imageFile->getError() == UPLOAD_ERR_OK) {
                    $imageFile->moveTo($project->image);
                }
            }

            $project->save();

            $status = 'Successful';
        } catch (NestedValidationException $e) {
            return implode(" - ",$e->findMessages([
                'stringType' => '{{name}} must contain a-z characters and/or simbols',
                'notEmpty' => "{{name}} can't be empty"            
            ]));
        }

        return $status;
    }

    public function delete($id)
    {
        try {
            $project = Project::findOrFail($id);

            $status = $project->delete();
        } catch (Exception $e) {
            $status = $e->getMessage();
        }

        return $status;
    }
}