<?php

declare(strict_types=1);

use Mockery\Mock;
use App\Model\Link;
use App\Util\CacheInterface;
use App\Actions\RetrieveLink;
use App\Exception\LinkNotFoundException;
use App\Repository\LinkRepositoryInterface;

beforeEach(function(){

    /** @var LinkRepositoryInterface|Mock */
    $this->linkRepository = Mockery::mock(LinkRepositoryInterface::class);

    /** @var CacheInterface|Mock */
    $this->cache = Mockery::mock(CacheInterface::class);
    
    $this->retriveLink = new RetrieveLink($this->linkRepository, $this->cache);
});


test('deve retornar um link pelo id do banco', function(){

    $link = new Link('Yuoo01', 'http://www.google.com');

    $this->linkRepository->shouldReceive('find')->once()->with('Yuoo01')->andReturn($link);
    $this->cache->shouldReceive('get')->once()->with('Yuoo01')->andReturn(false);
    $this->cache->shouldReceive('set')->once()->with('Yuoo01', 'http://www.google.com');
    
    $result = $this->retriveLink->execute('Yuoo01');


    expect($result)->toBe($link);
});

test('deve lançar exceção se link não existir',function(){
    
    $this->linkRepository->shouldReceive('find')->with('Yuoo01')->andReturn(null);
    $this->cache->shouldReceive('get')->with('Yuoo01')->andReturn(false);

    $result = $this->retriveLink->execute('Yuoo01');
})->throws(LinkNotFoundException::class);



test('deve ler do cache', function(){

    $this->cache->shouldReceive('get')->with('Yuoo01')->andReturn('http://www.google.com');

    $result = $this->retriveLink->execute('Yuoo01');

    expect($result)->toBeInstanceOf(Link::class);
});
