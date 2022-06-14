<?php 

declare(strict_types=1);

use App\Actions\StatisticsView;
use App\Repository\ViewRepositoryInterface;

beforeEach(function(){

    /** @var ViewRepository|Mock */
    $this->viewRepositoryMock = Mockery::mock(ViewRepositoryInterface::class);
    $this->statisticView = new StatisticsView( $this->viewRepositoryMock);

});


test('deve retornar estatisticas', function(){

    $this->viewRepositoryMock->shouldReceive('statisticsTotal')->with('Dee78d')->once()->andReturn(4);
    $this->viewRepositoryMock->shouldReceive('statisticsBrowser')->once()->andReturn([
        'Chrome' => 1,
        'Firefox' => 2,
        'Edge' => 1
    ]);
    $this->viewRepositoryMock->shouldReceive('statisticsOS')->with('Dee78d')->once()->andReturn([
        'Win10' => 3,
        'Linux' => 1
    ]);
    $this->viewRepositoryMock->shouldReceive('statisticsCountry')->with('Dee78d')->once()->andReturn([
        'BR' => 3,
        'US' => 1
    ]);

    $statistics = $this->statisticView->execute('Dee78d');

    expect($statistics)->toMatchArray([
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

});