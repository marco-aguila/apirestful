<?php

namespace App\Exceptions;


use Throwable;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        //condicion que retorno una respuesta en formato json de la clase convertValidationExceptionToResponse
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        //Mostrar el error con el nombre el Modelo y el tipo de error en json con el Trait ApiResponser
        if ($exception instanceof ModelNotFoundException) {
            //nombrer el nombre del modelo en minusculas sin el nombre de espacio
            $modelo = strtolower(class_basename( $exception->getModel()));
            //mensaje
            return $this->errorResponse("No existe ninguna instancia de [ {$modelo} ] con el id especificado", 404);
        }
        //condicion que retorna un mensaje de No autenticado
        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }
        //condicion que devuelve un mensaje si no posee los permisos suficientes
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse('No posee permisos para ejecutar esta accion', 403);
        }
        //Url no existente
        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('No se encontro la URL especificada', 404);
        }
        //Metodo no permitidos
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('El metodo especificado en la peticion no es valido en las variates del sistema.', 405);
        }
        //Controlar cualquier exception de tipo Http
        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }
        //Exception que muestra que hay una instancia relacionada con otro en la base de datos
        if ($exception instanceof QueryException) {
            $codigo = $exception->errorInfo[1];
            if ($codigo == 1451) {  
                return $this->errorResponse('No se puede eliminar de forma permamente el recurso porque esta relacionado con algun otro.', 409);
            }
        }

        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }
        //mostrar la exception si la app esta en modo de depuracion
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        //Exception Inesperada
        return $this->errorResponse('Falla Inesperada, Intente de nuevo', 500);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isFrontend($request)) {
            return redirect()->guest('login');
        }
        return $this->errorResponse('No autenticado', 401);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors  = $e->validator->errors()->getMessages();

        if ($this->isFrontend($request)) {
            return $request->ajax() ? response()->json($errors, 422) : redirect()
            ->back()
            ->withInput()
            ->withErrors($errors);
        }
        return $this->errorResponse($errors, 422);
    }
    
    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');

    }
}
