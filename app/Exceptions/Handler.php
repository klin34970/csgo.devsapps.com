<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
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
    public function render($request, Exception $exception)
    {
        //dd($exception);
        //dd($request->headers->get('accept'));
        if($request->headers->get('accept') == 'application/json'){
            if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'data' => [
                        'message'       => 'No query results',
                        'status_code'   => Response::HTTP_NOT_FOUND
                    ]
                ], Response::HTTP_NOT_FOUND);
            } elseif ($exception instanceof \Illuminate\Database\QueryException) {
                switch($exception->getCode()){
                    case 1045:
                        return response()->json([
                            'data' => [
                                'message'      => 'Access denied to the database',
                                'status_code'   => Response::HTTP_SERVICE_UNAVAILABLE
                            ]
                        ], Response::HTTP_SERVICE_UNAVAILABLE);
                        break;
                    case 2002:
                        return response()->json([
                            'data' => [
                                'message'      => 'Name or service not known',
                                'status_code'   => Response::HTTP_SERVICE_UNAVAILABLE
                            ]
                        ], Response::HTTP_SERVICE_UNAVAILABLE);
                        break;
                    case 23000:
                        return response()->json([
                            'data' => [
                                'message'      => 'Integrity constraint violation',
                                'status_code'   => Response::HTTP_CONFLICT
                            ]
                        ], Response::HTTP_CONFLICT);
                        break;
                }

            } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response()->json([
                    'data' => [
                        'message'       => 'The requested resource doesn\'t exists',
                        'status_code'   => Response::HTTP_NOT_FOUND
                    ]
                ], Response::HTTP_NOT_FOUND);
            } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                return response()->json([
                    'data' => [
                        'message'       => 'The request method is not supported for the requested resource',
                        'status_code'   => Response::HTTP_METHOD_NOT_ALLOWED
                    ]
                ], Response::HTTP_METHOD_NOT_ALLOWED);
            } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json([
                    'data' => [
                        'message'       => 'Needs to authenticate to gain network access',
                        'status_code'   => Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED
                    ]
                ], Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED);
            } elseif ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'data' => [
                        'message'       => 'The user does not have the necessary credentials',
                        'status_code'   => Response::HTTP_UNAUTHORIZED
                    ]
                ], Response::HTTP_UNAUTHORIZED);
            } elseif ($exception instanceof Exception) {
                return response()->json([
                    'data' => [
                        'message'       => $exception->getMessage(),
                        'status_code'   => Response::HTTP_NOT_FOUND
                    ]
                ], Response::HTTP_NOT_FOUND);
            } elseif ($exception instanceof \ReflectionException) {
                return response()->json([
                    'data' => [
                        'message'       => $exception->getMessage(),
                        'status_code'   => Response::HTTP_NOT_FOUND
                    ]
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'data' => [
                    'message'           => $exception->getMessage(),
                    'status_code'       => Response::HTTP_BAD_REQUEST
                ]
            ], Response::HTTP_BAD_REQUEST);
        }

        return parent::render($request, $exception);
    }
}
