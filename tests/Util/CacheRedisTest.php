<?php

declare(strict_types=1);

use App\Util\CacheRedis;

beforeEach(function(){
    $this->redis = new \Redis();
    $this->redis->connect('127.0.0.1', 6379);

    $this->cacheRedis = new CacheRedis($this->redis);
});


test('deve ler do cache', function(){

    $this->redis->set('chave','valor');

    $value = $this->cacheRedis->get('chave');

    expect($value)->toBe('valor');

});

test('deve gravar no cache', function(){
    
    $this->cacheRedis->set('chave','valor');

    $value = $this->redis->get('chave');

    expect($value)->toBe('valor');
});


test('deve gravar no cache com expiracao', function(){
    
    $this->cacheRedis->set('chave','valor', 1);

    $value = $this->redis->get('chave');

    expect($value)->toBe('valor');

    sleep(2);

    $value = $this->redis->get('chave');

    expect($value)->toBeFalse();
});