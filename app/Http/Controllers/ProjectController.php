<?php

namespace App\Http\Controllers;

use App\Model\Project;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Controllers\ApiController;

/**
* @OA\Info(title="API Proyectos", version="1.0")
*
* @OA\Server(url="https://backendchallenge-estoes.herokuapp.com/")
*/

class ProjectController extends ApiController
{

/**    @OA\SecurityScheme(
    *     type="http",
    *     in="header",
    *     scheme="bearer",
    *     bearerFormat="JWT",
    *     securityScheme="apiAuth",
    *     )
    * @OA\Get(
    *     path="/api/projects",
    *     summary="Mostrar proyectos",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los proyectos."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     ),
    *     security={{"apiAuth":{} }}
    * )
    */
    public function index()
    {
        
        $projects = Project::paginate(5);
        return $projects;
    
    }
        /** 
    * @OA\Post(
    *     path="/api/projects",
    *     summary="Nuevo proyecto",
    *     description="Crea un nuevo proyecto",
    *    @OA\RequestBody(
     *         required=true,
     *         description="Datos del nuevo proyecto",
     *      @OA\JsonContent(
     *              required={"name","description","status"},
     *              @OA\Property(property="name", type="string", format="text", example="Proyecto laravel"),
     *              @OA\Property(property="description", type="string", format="text", example="This project runs with Laravel version 5.7"),
     *              @OA\Property(property="status", type="string", format="text", example="enabled"),
     *           ),
     *  ),
    *   @OA\Response(
    *         response=401,
    *         description="Ha ocurrido un error."
    *     ),
    *   security={{"apiAuth":{} }}
    * )
    */
    public function store(ProjectRequest $request)
    {
        $data = $request->all();
        $project = Project::create($data);
        return $this->showOne($project, 201);
        
    }

            /** 
    * @OA\Patch(
    *     path="/api/projects/{id}",
    *  @OA\Parameter(
    *        in= "path",
    *        name= "id",
    *         example= 2,
    *         required= true
    *       ),
    *     summary="Actualiza proyecto",
    *     description="Actualiza un proyecto existente",
    *    @OA\RequestBody(
     *         required=true,
     *         description="Datos a modificar del proyecto",
     *      @OA\JsonContent(
     *              @OA\Property(property="name", type="string", format="text", example="Proyecto Nodejs"),
     *              @OA\Property(property="description", type="string", format="text", example="This project runs with NodeJS"),
     *              @OA\Property(property="status", type="string", format="text", example="enabled"),
     *           ),
     *  ),
    *   @OA\Response(
    *         response=401,
    *         description="Ha ocurrido un error."
    *     ),
    *   security={{"apiAuth":{} }}
    * )
    */

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
        /** 
    * @OA\Delete(
    *     path="/api/projects/{id}",
    *     @OA\Parameter(
    *        in= "path",
    *        name= "id",
    *         example= 1,
    *         required= true
    *       ),
    *     summary="Elimina un proyecto",
    *     description="Elimina un proyecto existente",
    *   @OA\Response(
    *         response=401,
    *         description="Ha ocurrido un error."
    *     ),
    *   security={{"apiAuth":{} }}
    * )
    */
    public function destroy($id){

        $project = Project::where('id', '=', $id)->firstOrFail();
        $project->delete();
        return $this->showOne($project, 201);
    }
        /** 
    * @OA\Get(
    *     path="/api/projects/{id}",
    *     @OA\Parameter(
    *        in= "path",
    *        name= "id",
    *         example= 3,
    *         required= true
    *       ),
    *     summary="Muestra un proyecto",
    *     description="Muestra un proyecto existente /id",
    *   @OA\Response(
    *         response=401,
    *         description="Ha ocurrido un error."
    *     ),
    *   security={{"apiAuth":{} }}
    * )
    */
    public function show($id)
    {
        $project = Project::where('id', '=', $id)->firstOrFail();
        return $this->showOne($project, 201);
        
    }

        /** 
    * @OA\Get(
    *     path="/api/buscar/?name={name}",
    *     @OA\Parameter(
    *        in= "path",
    *        name= "name",
    *         example="Proyecto",
    *         required= true
    *       ),
    *     summary="Busca proyectos por nombres",
    *     description="Permite realizar una búsqueda por nombre de proyecto. ",
    *   @OA\Response(
    *         response=401,
    *         description="Ha ocurrido un error."
    *     ),
    *   security={{"apiAuth":{} }}
    * )
    */
    public function buscar(Request $request)
    {

        $project = Project::query();

         if(!is_null($request->name)) {
        $project->orWhere("name", "LIKE", "%{$request->name}%");
         }

        if (!is_null($request->start_at)) {
            $project->orWhere('start_at', '>=', $request->start_at);
        }

        if (!is_null($request->end_at)) {
            $project->orWhere('end_at', '<=', $request->end_at);
        }

        return $project->get();
}
}
