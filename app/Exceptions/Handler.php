<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //

        
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */

    protected function unauthenticated($request, AuthenticationException $exception){
        
        return $this->errorResponse('No autenticado. ', 401);

    }

    public function render($request, Exception $exception)
    {

        
         if($exception instanceof ValidationException){

            return $this->convertValidationExceptionToResponse($exception, $request);
        }

         if($exception instanceof ModelNotFoundException){

            $modelo = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No existe ninguna instancia " . $modelo . " con el id especificado", 404);
        }

        if ($exception instanceof AuthenticationException) {

            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            
            return $this->errorResponse('No posee permisos para esta accion', 403);
        }

        if ($exception instanceof NotFoundHttpException) {

            return $this->errorResponse('No se encontro la URL especificada', 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {

            return $this->errorResponse('El metodo especificado en la peticion no es valido', 405);
        }

         if ($exception instanceof HttpException) {

            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }

        if ($exception instanceof QueryException) {

            $codigo = $exception->errorInfo[1];

            if($codigo == 1451){

                return $this->errorResponse('No se puede eliminar de forma permanente el recurso porque esta relacionado con algun otro.', 409);
            }

            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }

        if (config('app.debug')){
            
            return parent::render($request, $exception);
        }

        return $this->errorResponse('Falla inesperada. Intente luego. ', 500);
    }

    
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        
        return $this->errorResponse($errors, 422);

    }

    




}