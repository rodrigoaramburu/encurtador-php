<?php

declare(strict_types=1);

use App\Model\Link;
use App\Actions\GenerateLink;
use App\Actions\RetrieveLink;
use App\Exception\IdExistsException;
use App\Exception\LinkNotFoundException;
use App\Repository\LinkRepositoryInterface;

beforeEach(function(){
    $bootstrap = require(__DIR__ . '/../../app/bootstrap.php');
    $this->app = $bootstrap();
    
    $errorMiddleware = require(__DIR__.'/../../app/middleware/error.php');
    $errorMiddleware($this->app);
    
    $routes = require(__DIR__ . '/../../app/routes.php');
    $routes($this->app);

    $this->generateLink = Mockery::mock(GenerateLink::class);
    $this->retrieveLink = Mockery::mock(RetrieveLink::class);

    $this->app->getContainer()->set(GenerateLink::class, $this->generateLink);
    $this->app->getContainer()->set(RetrieveLink::class, $this->retrieveLink);
});

test('deve retornar um link', function(){
    $this->retrieveLink
        ->shouldReceive('execute')
        ->with('UES4d2')->andReturn(new Link('UES4d2', 'http://www.google.com'));

    /** @var Request */
    $request = createRequest(method: 'GET', path: '/UES4d2');
    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(301);
    expect($response->getHeader('Location')[0])->toBe('http://www.google.com');
});

test('deve retornar não encontrado se id não existir', function(){

    $this->retrieveLink
        ->shouldReceive('execute')
        ->with('UES4d2')
        ->andThrow(new LinkNotFoundException('Link não encontrado'));

    /** @var Request */
    $request = createRequest(method: 'GET', path: '/UES4d2');
    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(404);
});

test('deve salvar um link', function(){
    $this->generateLink
        ->shouldReceive('execute')
        ->with('http://www.google.com', null)
        ->andReturn(new Link('UES4d2', 'http://www.google.com'));

    /** @var Request */
    $request = createRequest(method: 'POST', path: '/api/encurtar', body: json_encode([
        'url' => 'http://www.google.com',
    ]));


    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(201);
    expect($response->getHeader('Content-Type')[0])->toBe('application/json');
    expect((string)$response->getBody())->json()
        ->id->toBe("UES4d2")
        ->url->toBe('http://www.google.com');
});


test('deve salvar um link com id customizado', function(){
    $this->generateLink
        ->shouldReceive('execute')
        ->with('http://www.google.com', 'GOOGLE')
        ->andReturn(new Link('GOOGLE', 'http://www.google.com'));

    /** @var Request */
    $request = createRequest(method: 'POST', path: '/api/encurtar', body: json_encode([
        'url' => 'http://www.google.com',
        'id' => 'GOOGLE'
    ]));


    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(201);
    expect($response->getHeader('Content-Type')[0])->toBe('application/json');
    expect((string)$response->getBody())->json()
        ->id->toBe("GOOGLE")
        ->url->toBe('http://www.google.com');
});

test('deve gerar erro se id já existir', function(){
    $this->generateLink
        ->shouldReceive('execute')
        ->with('http://www.google.com', 'GOOGLE')
        ->andThrow(new IdExistsException('Id já existe'));

    /** @var Request */
    $request = createRequest(method: 'POST', path: '/api/encurtar', body: json_encode([
        'url' => 'http://www.google.com',
        'id' => 'GOOGLE'
    ]));


    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(409);
    expect($response->getHeader('Content-Type')[0])->toBe('application/json');
    expect((string)$response->getBody())->json()
        ->message->toBe("Id já existe")
        ->code->toBe(409);
});


test('deve gerar erro se url não enviada', function(){

    /** @var Request */
    $request = createRequest(method: 'POST', path: '/api/encurtar', body: json_encode([
    ]));


    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(400);
    expect((string) $response->getBody())->json()
        ->code->toBe(400)
        ->message->toBe('URL não informada');
});


test('deve gerar erro se endereço não existe',function(){
    /** @var Request */
    $request = createRequest(method: 'GET', path: '/api/nao-encontrado' );

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(404);
    expect((string) $response->getBody())->json()
        ->code->toBe(404)
        ->message->toBe('Not found.');
});

test('deve gerar erro se exceção ocorrer',function(){

    $this->app->get('/api/exception',function(){
        throw new \Exception('Erro');
    });

    /** @var Request */
    $request = createRequest(method: 'GET', path: '/api/exception' );

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(500);
    expect((string) $response->getBody())->json()
        ->code->toBe(500)
        ->message->toBe('Internal Error.');
});