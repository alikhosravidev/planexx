<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Services\ResponseBuilder;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

abstract class BaseException extends Exception implements Responsable
{
    public function __construct(string $message = '', private array $errors = [])
    {
        parent::__construct($message, $this->statusCode()->value);
    }

    public function toResponse($request)
    {
        return $this->render($request);
    }

    public function render($request)
    {
        if ($this->isApi($request)) {
            return $this->renderApi();
        }

        return $this->renderWeb($request);
    }

    public function report(): void
    {
        //resolve(ExceptionCaptureService::class)->capture($this);
    }

    abstract protected function statusCode(): HttpStatusCodeEnum;

    private function isApi(Request $request): bool
    {
        return $request->ajax() || $request->is('api/*');
    }

    private function renderWeb(Request $request): RedirectResponse|Response
    {
        $previousFullUrl = url()->previous();
        $previousPath    = $previousFullUrl ? parse_url($previousFullUrl, PHP_URL_PATH) : null;
        $currentPath     = $request->getPathInfo();
        $isServerError   = $this->statusCode()->value >= 500 && $this->statusCode()->value < 600;

        if (!$previousFullUrl || ($previousPath === $currentPath && $isServerError)) {
            return response()->view(
                'errors.500',
                ['errors' => empty($this->errors) ? $this->getSafeMessage() : $this->errors],
                status: HttpStatusCodeEnum::HTTP_INTERNAL_SERVER_ERROR->value
            );
        }

        return back()->withErrors(empty($this->errors) ? $this->getSafeMessage() : $this->errors);
    }

    private function renderApi(): JsonResponse
    {
        $responder = new ResponseBuilder();

        $message = $this->getSafeMessage();

        $response = $responder
            ->setMessage($message)
            ->setStatusCode($this->code)
            ->setErrors($this->errors)
            ->build();

        // Add meta header to identify exception type for defensive programming
        $response->header('X-Is-Business-Exception', $this instanceof BusinessException ? 'true' : 'false');

        return $response;
    }

    private function getSafeMessage(): string
    {
        if ($this instanceof BusinessException) {
            return $this->message;
        }

        Log::error($this->message, [
            'exception' => $this,
        ]);

        return trans('errors.failed_process');
    }
}
