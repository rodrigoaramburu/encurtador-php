<?php

declare(strict_types=1);

use App\Actions\EnqueueView;
use App\Util\QueueInterface;

beforeEach(function(){


    /** @var QueueInterface|Mock */
    $this->queue = Mockery::mock(QueueInterface::class);
    
    $this->queueView = new EnqueueView($this->queue);
});

test('deve gravar um job na fila', function(){

    $_SERVER['REMOTE_ADDR'] = '';
    $request = createRequest(method: 'GET', path: '/UES4d2', headers: [
        'User-Agent' => 'Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/102.0.5005.115 Safari\/537.36'
        ],
        serverParams: [
            'REMOTE_ADDR' => '192.168.0.100'
        ]);
    
    $this->queue->shouldReceive('queue')->once()->with( Mockery::on( function($param){
        $data = json_decode($param, true);
        
        return $data['id'] === 'UES4d2' &&
            $data['ip'] === '192.168.0.100' &&
            $data['user-agent'] === 'Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/102.0.5005.115 Safari\/537.36' &&
            str_starts_with($data['access_at'], (new DateTime())->format('Y-m-d H'));

    }));


    $this->queueView->execute('UES4d2', $request);

});