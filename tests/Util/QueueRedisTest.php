<?php 

declare(strict_types=1);

use App\Util\QueueRedis;

beforeEach(function(){
    $this->redis = new \Redis();
    $this->redis->connect('127.0.0.1', 6379);
    $this->redis->flushAll();

    $this->queueRedis = new QueueRedis($this->redis);
});



test('deve enfileira um jobs', function(){

    $this->queueRedis->queue('value1');
    $this->queueRedis->queue('value2');


    $value1 =  $this->queueRedis->dequeue();
    $value2 =  $this->queueRedis->dequeue();

    expect($value1)->toBe('value1');
    expect($value2)->toBe('value2');
});