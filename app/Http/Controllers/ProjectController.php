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

    public function update(Request $request, Project $project)
    {    

         $rules = [

            'name' => 'string',
            'description' => 'string',
            'status' => 'string',
            
        ];

        $this->validate($request, $rules);
            
        
        $project->fill($request->only([
            'name',
            'description',
            'status',

         ]));
            
        if($project->isClean()) {
            return $this->errorResponse('Debe especificarse al menos un valor distinto para continuar con la actualizacion', 422);
        }

        
        $project->save();
        return $this->showOne($project, 201);


    }

    public function destroy($id){

        $project = Project::where('id', '=', $id)->firstOrFail();
        $project->delete();
        return $this->showOne($project, 201);
    }
}
