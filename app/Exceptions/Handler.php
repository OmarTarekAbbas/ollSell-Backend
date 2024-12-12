<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Modules\Basic\Traits\ApiResponseTrait;
use Modules\Setting\Entities\RequestLog;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    public function render($request, $e)
    {
        if($e instanceof \Exception)
        {


        //    requestLog($request,$e);

        }
        if($e instanceof NotFoundHttpException)
        {
            if($request->expectsJson())
            {
                return $this->notFoundResponse('support' . " url not Found");
            }
        }
        if($e instanceof AuthenticationException)
        {
            if($request->expectsJson())
            {
                return $this->unauthorizedResponse('login first');
            }
        }
        if($e instanceof MethodNotAllowedHttpException)
        {
            return $this->methodNotAllowed('support' . " " . $e->getMessage());
        }
        if($e instanceof ModelNotFoundException)
        {
            return $this->unKnowError('support' . " " . $e->getMessage());
        }
        if($e instanceof Throwable)
        {
          //  requestLog($request,$e);
            if($this->isHttpException($e))
            {
                switch($e->getStatusCode())
                {
                    // not found
                    case 404:
                        return redirect()->route('dashboard');
                        break;
                    default:
                        return $this->renderHttpException($e);
                        break;
                }
            }else
            {
                return parent::render($request, $e);
            }
        }
        return parent::render($request, $e);
    }

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];
    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     * return void
     */
    public function register()
    {
        $this->reportable(function(Throwable $e)
        {
            if(app()->bound('sentry'))
            {
                app('sentry')->captureException($e);
            }
        });
        $this->reportable(function(Throwable $e)
        {
            //
        });
    }
}
