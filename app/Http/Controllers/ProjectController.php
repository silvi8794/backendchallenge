<?php

namespace App\Http\Controllers;

use App\Model\Project;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Controllers\ApiController;

class ProjectController extends ApiController
{
    public function store(ProjectRequest $request)
    {
        $data = $request->all();
        $project = Project::create($data);
        return $this->showOne($project, 201);
        
    }
}
