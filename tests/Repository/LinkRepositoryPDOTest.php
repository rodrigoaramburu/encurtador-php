<?php

declare(strict_types=1);

use App\Model\Link;
use App\Exception\IdExistsException;
use App\Repository\LinkRepositoryPDO;

uses(PDOHelper::class);

beforeEach( function(){
    $this->initPDO()->loadCreateTable( __DIR__.'/../../database/create.sql');
    $this->repository = new LinkRepositoryPDO( $this->pdoConn());
});

test('deve salvar um link', function(){
   
    $link = new Link(id: 'YUx021', url: 'http://www.google.com');

    $this->repository->save($link);

    $this->assertDatabaseHas('links',[
        'id' => 'YUx021',
        'url' => 'http://www.google.com',
    ]);
    
});

test('deve retornar um link pelo id', function(){

    $this->pdoConn()->exec('INSERT INTO links (id, url) VALUES ("YUx021","http://www.google.com")');

    $link = $this->repository->find('YUx021');
    
    expect($link->id())->toBe('YUx021');
    expect($link->url())->toBe('http://www.google.com');    
});


test('deve retornar null quando não encontrar o link', function(){
    $link = $this->repository->find('YUx021');
    expect($link)->toBeNull();
});


test('deve lançar exceção se id ja existir', function(){
    $this->pdoConn()->exec('INSERT INTO links (id, url) VALUES ("YUx021","http://www.google.com")');

    $link = new Link(id: 'YUx021', url: 'http://www.google.com');

    $this->repository->save($link);
    
})->throws(IdExistsException::class);