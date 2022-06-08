<?php

declare(strict_types=1);

use Slim\App;
use Monolog\Logger;
use Slim\Psr7\Response;
use Slim\Exception\HttpException;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

return function(App $app){
    $logger = new Logger('log');
    $formatter = new LineFormatter(
        null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
        null, // Datetime format
        true, // allowInlineLineBreaks option, default false
        true  // ignoreEmptyContextAndExtra option, default false
    );

    $stream = new StreamHandler( __DIR__ . '/../../logs/encurtador.log', Logger::DEBUG);
    $stream->setFormatter($formatter);
    $logger->pushHandler($stream);
    
    $app->add(new class($logger) implements MiddlewareInterface{
        
        public function __construct(
            private Logger $logger
        ){
            
        }

        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            try {
            
                return $handler->handle($request);
            
            }catch(HttpException $e){
                
                $response = new Response();
                $response->getBody()->write(json_encode([
                    'message' => $e->getMessage(), 
                    'code' => $e->getCode()
                ]));
                return $response->withStatus($e->getCode());
            }catch (Throwable $e) {

                $this->logger->error($e->getMessage());
                $this->logger->error($e->getTraceAsString());
            
                $response = new Response();

                $response->getBody()->write(json_encode([
                    'message' => 'Internal Error.', 
                    'code' => 500
                ]));

                return $response->withStatus(500);
            }
        }
    });

};