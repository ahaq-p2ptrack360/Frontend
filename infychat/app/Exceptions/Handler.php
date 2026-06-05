<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        OAuthServerException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $e
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        $code = $e->getCode();
        $message = $e->getMessage();

        if ($code < 100 || $code >= 600) {
            $code = \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        if ($e instanceof AuthenticationException || $e instanceof UnauthorizedHttpException) {
            $code = \Illuminate\Http\Response::HTTP_UNAUTHORIZED;
        }

        if ($e instanceof ModelNotFoundException) {
            $message = $e->getMessage();
            $code = \Illuminate\Http\Response::HTTP_NOT_FOUND;

            if (preg_match('@\\\\(\w+)\]@', $message, $matches)) {
                $model = $matches[1];
                $model = preg_replace('/Table/i', '', $model);
                $message = "{$model} not found.";
            }
        }

        if ($e instanceof ValidationException) {
            $validator = $e->validator;
            $message = $validator->errors()->first();
            $code = \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY;
        }

        if ($e instanceof PostTooLargeException) {
            $code = \Illuminate\Http\Response::HTTP_REQUEST_ENTITY_TOO_LARGE;
            $message = 'File too large';
        }

        if ($request->expectsJson() || $request->isXmlHttpRequest()) {
            return Response::json([
                'success' => false,
                'message' => $message,
            ], $code);
        }

        return parent::render($request, $e);
    }
}
