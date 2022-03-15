<?php

namespace App\Exceptions;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Exception;
use App\Traits\ApiResponse;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    // public function register()
    // {
    //     $this->reportable(function (Throwable $e) {
    //         //
    //     });
    // }

    public function register()
    {
         $this->renderable(function(Exception $e, $request) {
             return $this->handleException($request, $e);
         });
     }

     public function handleException($request, Exception $exception)
     {

         if($exception instanceof HttpException) {
            return ApiResponse::errorResponse($exception->getMessage(), 400);
         }

         if($exception instanceof NotFoundHttpException) {
            return ApiResponse::errorResponse('The specified URL cannot be  found.', 404);
         }

     }

}
