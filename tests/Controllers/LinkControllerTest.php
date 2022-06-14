<?php

declare(strict_types=1);

use App\Actions\EnqueueViewInterface;
use App\Model\Link;
use App\Actions\GenerateLinkInterface;
use App\Actions\RetrieveLinkInterface;
use App\Actions\StatisticsViewInterface;
use App\Exception\IdExistsException;
use Psr\Http\Message\RequestInterface;
use App\Exception\LinkNotFoundException;

beforeEach(function(){

    $this->app = (require(__DIR__ . '/../../config/bootstrap.php'))();

    $this->generateLink = Mockery::mock(GenerateLinkInterface::class);
    $this->retrieveLink = Mockery::mock(RetrieveLinkInterface::class);
    $this->queueView = Mockery::mock(EnqueueViewInterface::class);
    $this->statisticsView = Mockery::mock(StatisticsViewInterface::class);

    $this->app->getContainer()->set(GenerateLinkInterface::class, $this->generateLink);
    $this->app->getContainer()->set(RetrieveLinkInterface::class, $this->retrieveLink);
    $this->app->getContainer()->set(EnqueueViewInterface::class, $this->queueView);
    $this->app->getContainer()->set(StatisticsViewInterface::class, $this->statisticsView);
});

test('deve retornar um link', function(){
    $this->retrieveLink
        ->shouldReceive('execute')
        ->with('UES4d2')->andReturn(new Link('UES4d2', 'http://www.google.com'));

    $this->queueView->shouldReceive('execute')->with('UES4d2', Mockery::type(RequestInterface::class));

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

    $this->retrieveLink->shouldReceive('execute')->andThrow(new \Exception('Erro'));
    

    /** @var Request */
    $request = createRequest(method: 'GET', path: '/AdC32E' );

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(500);
    expect((string) $response->getBody())->json()
        ->code->toBe(500)
        ->message->toBe('Internal Error.');
});



test('deve retornar as estatisticas de um link', function(){
   

    $this->statisticsView->shouldReceive('execute')->with('UES4d2')->andReturn([
        'total' => 4,
        'browsers' => [
            'Chrome' => 1,
            'Firefox' => 2,
            'Edge' => 1
        ],
        'os' => [
            'Win10' => 3,
            'Linux' => 1
        ],
        'countries' => [
            'BR' => 3,
            'US' => 1
        ],
    ]);

    /** @var Request */
    $request = createRequest(method: 'GET', path: '/api/statistics/UES4d2');
    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(200);
    expect((string) $response->getBody())->json()
        ->total->toBe(4)
        ->browsers->toBe([
            'Chrome' => 1,
            'Firefox' => 2,
            'Edge' => 1
        ])
        ->os->toBe([
            'Win10' => 3,
            'Linux' => 1
        ])
        ->countries->toBe([
            'BR' => 3,
            'US' => 1
        ]);
    
});

test('deve lançar exceção se id do link não existir quando visualizar estatistica', function(){

    $this->statisticsView
        ->shouldReceive('execute')
        ->with('UES4d2')
        ->andThrow( new LinkNotFoundException('Link não encontrado') );

    /** @var Request */
    $request = createRequest(method: 'GET', path: '/api/statistics/UES4d2');
    $response = $this->app->handle($request);

    expect((string) $response->getBody())->json()
        ->code->toBe(404)
        ->message->toBe('Link não encontrado');
});