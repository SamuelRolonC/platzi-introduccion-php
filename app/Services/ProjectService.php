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
    public function create(array $projectData)
    {
        $projectValidator = Validator::key('title',Validator::stringType()->notEmpty())
            ->key('description',Validator::stringType()->notEmpty());

        try {
            $projectValidator->assert($projectData);

            $project = new Project;
            if (isset($projectData['id'])) {
                $project->id_project = $projectData['id'];
            }
            $project->title = $projectData['title'];
            $project->description = $projectData['description'];
            $project->visible = 1;
            $project->months = 0;
            $project->id_user = $_SESSION['userId'];

            $imageFile = $projectData['imageFile'];

            if ($imageFile->getError() == UPLOAD_ERR_OK) {
                $imageFile->moveTo($project->image);
            }

            $project->save();

            $status = 'Successful';
        } catch (Exception $e) {
            $status = $e->getMessage();
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