<?php


namespace App\Services;


use App\Models\Job;

class JobService
{
    public function delete($id)
    {
        $job = Job::find($id);
        $job->delete();
    }
}