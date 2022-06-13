<?php

declare(strict_types=1);

use App\Model\View;
use App\Actions\DequeueView;
use App\Util\QueueInterface;
use App\Repository\ViewRepositoryInterface;

beforeEach(function(){
    /** @var QueueInterface|Mock */
    $this->queue = Mockery::mock(QueueInterface::class);

    /** @var ViewRepositoryInterface::class|Mock */
    $this->viewRepository = Mockery::mock(ViewRepositoryInterface::class);
    
    $this->dequeueView = new DequeueView($this->queue, $this->viewRepository);
});


test('deve ler job da fila e processar', function(){
    $this->queue->shouldReceive('dequeue')->once()->andReturn(json_encode([
        'id' => 'UES4d2',
        'ip' => '192.168.0.100',
        'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.115 Safari/537.36',
        'access_at' => '2020-01-01 12:00:00'
    ]));


    $this->viewRepository->shouldReceive('save')->once()->with(Mockery::on(function(View $param){

        return $param->id() === 'UES4d2' &&
            $param->ip() === '192.168.0.100' &&
            $param->accessAt()->format('Y-m-d H:i:s') === '2020-01-01 12:00:00' &&
            $param->browser() === 'Chrome' &&
            $param->os() === 'Win10' &&
            $param->countryISO() === 'Unknown' &&
            $param->countrySubdivisionISO() === 'Unknown';
    }));

    $view = $this->dequeueView->execute();

    expect($view)->toBeInstanceOf(View::class);
});


test('deve retornar null se não houver job na fila', function(){
    $this->queue->shouldReceive('dequeue')->once()->andReturn(false);

    $view = $this->dequeueView->execute();

    expect($view)->toBeNull();
});