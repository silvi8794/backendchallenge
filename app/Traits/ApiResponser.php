<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;


Trait ApiResponser
{
    //----ApiResponse tendra el codigo necesario para construir las respuestas de nuestra API----//

    //*---Metodo successResponse
    //Metodo encargado de construir respuestas satisfactorias
    //en formato JSON
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    //*---Metodo errorResponse
    //Metodo encargado de construir respuestas de error
    //en formato JSON
    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    //*---Metodo showAll
    //Metodo encargado de mostrar una respuesta con multiples elementos,
    //es decir, una coleccion, por ej. cuando se retorna la lista de todos los alumnos
    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty()){

            return $this->successResponse([ 'data' => $collection], $code);
        }
        
    }

    //*---Metodo showOne
    //Metodo encargado de mostrar una respuesta con una instancia especifica 
    //de un Modelo, por ej. cuando retornamos una instancia de un curso existente
    protected function showOne(Model $instance, $code = 200)
    {

        //return $this->successResponse($instance, $code);
        return $this->successResponse(['data' => $instance], $code);
    }


    protected function showMessage($message, $code = 200)
    {
        
        return $this->successResponse(['data' => $message], $code);
    }




}
