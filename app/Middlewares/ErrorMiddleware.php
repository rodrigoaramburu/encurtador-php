<?php

declare(strict_types=1);

namespace App\Middlewares;

use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpException;
use Slim\Psr7\Response;
use Throwable;

final class ErrorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Logger $logger
    ) {
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (HttpException $e) {
            $response = new Response();
            $response->getBody()->write(json_encode([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]));
            return $response->withStatus($e->getCode());
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());

            $response = new Response();

            $response->getBody()->write(json_encode([
                'message' => 'Internal Error.',
                'code' => 500,
            ]));

            return $response->withStatus(500);
        }
    }
}
