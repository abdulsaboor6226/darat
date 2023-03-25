<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        
        

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
    // public function render($request, Throwable $exception)
    // {

    //     if($request->is('api/*')){
    //         if ($exception instanceof ValidationException) {
    //             $errors = $exception->validator->errors();
    //             $error = $errors->all();

    //             return response()->json([
    //                 'error' => true,
    //                 'message' => $error[0],
    //                 // 'errors' => [
    //                 //     $exception->validator->errors()
    //                 // ]
    //             ], 200);
    //         }

    //         return response()->json(['error' => $exception->getMessage()], 200);
    //     }

    //     if ($exception instanceof ValidationException){
    //         Alert::toast($exception->getMessage(), 'error');
    //         return back()->withErrors($exception->getMessage());
    //     }

    // }
}
