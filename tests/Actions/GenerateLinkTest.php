<?php

declare(strict_types=1);

use Mockery\Mock;
use App\Actions\GenerateLink;
use App\Exception\IdExistsException;
use App\Exception\GenerateLinkException;
use App\Repository\LinkRepositoryInterface;

beforeEach(function(){
    /** @var  LinkRepositoryInterface|Mock */
    $this->linkRepository = Mockery::mock(LinkRepositoryInterface::class);

    $this->generateLink = new GenerateLink( linkRepository: $this->linkRepository );
});

test('deve gerar um link', function () {
   
    $this->linkRepository->shouldReceive('save')->once();
   
    $link = $this->generateLink->execute(url: 'http://www.google.com.br');

    expect($link->url())->toBe('http://www.google.com.br');
    expect( strlen($link->id()))->toBe(6);
});

test('deve gerar um link com id customizado', function () {
   
    $this->linkRepository->shouldReceive('save')->once();
   
    $link = $this->generateLink->execute(id: 'MEULINK', url: 'http://www.google.com.br');

    expect($link->url())->toBe('http://www.google.com.br');
    expect($link->id())->toBe('MEULINK');
});


test('deve tentar gerar id varias vezes se houver colizão na geração', function(){

    $this->linkRepository
        ->shouldReceive('save')
        ->times(1)
        ->andThrow(new IdExistsException('id já existe'));

    $this->linkRepository->shouldReceive('save');
   
    $link = $this->generateLink->execute(url: 'http://www.google.com.br');

    expect($link->url())->toBe('http://www.google.com.br');
    expect( strlen($link->id()))->toBe(6);

});

test('deve lançar exceção se id customizado já existir', function(){
    $this->linkRepository
        ->shouldReceive('save')
        ->once()
        ->andThrow(new IdExistsException('id já existe'));


    $this->generateLink->execute(id: 'MEULINK', url: 'http://www.google.com.br');
    
})->throws(IdExistsException::class);


test('deve lançar exceção ao tentar gerar id por 10 vezes', function(){
    $this->linkRepository
        ->shouldReceive('save')
        ->times(10)
        ->andThrow(new IdExistsException('id já existe'));

    $this->generateLink->execute(url: 'http://www.google.com.br');

})->throws(GenerateLinkException::class);