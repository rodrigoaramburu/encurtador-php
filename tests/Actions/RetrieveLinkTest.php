<?php

declare(strict_types=1);

use Mockery\Mock;
use App\Model\Link;
use App\Actions\RetrieveLink;
use App\Exception\LinkNotFoundException;
use App\Repository\LinkRepositoryInterface;

beforeEach(function(){

    /** LinkRepositoryInterface|Mock */
    $this->linkRepository = Mockery::mock(LinkRepositoryInterface::class);
    
    $this->retriveLink = new RetrieveLink($this->linkRepository);
});


test('deve retornar um link pelo id', function(){

    $link = new Link('Yuoo01', 'http://www.google.com');

    $this->linkRepository->shouldReceive('find')->with('Yuoo01')->andReturn($link);

    $result = $this->retriveLink->execute('Yuoo01');

    expect($result)->toBe($link);
});

test('deve lançar exceção se link não existir',function(){
    
    $this->linkRepository->shouldReceive('find')->with('Yuoo01')->andReturn(null);

    $result = $this->retriveLink->execute('Yuoo01');
})->throws(LinkNotFoundException::class);