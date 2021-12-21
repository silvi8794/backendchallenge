<?php

namespace App\Http\Controllers;

use App\Model\Project;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;

class ProjectController extends Controller
{
    public function store(ProjectRequest $request)
    {
        $data = $request->all();
        $project = Project::create($data);
        return $project;
        
    }
}
