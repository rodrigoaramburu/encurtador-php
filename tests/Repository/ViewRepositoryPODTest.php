<?php

declare(strict_types=1);

use App\Model\View;
use App\Repository\ViewRepositoryPDO;

uses(PDOHelper::class);

beforeEach( function(){
    $this->initPDO()->loadCreateTable( __DIR__.'/../../database/create.sql');
    $this->repository = new ViewRepositoryPDO( $this->pdoConn());
});


test('deve salvar uma visualização de link encurtado', function(){

    $view = new View(
        id: 'YUx021', 
        ip: '192.168.0.100',
        browser: 'Chrome',
        os: 'Windows',
        accessAt: new DateTime('2020-01-01 00:00:00'),
        countryISO: 'BR',
        countrySubdivisionISO: 'RS'
    );

    $this->repository->save($view);

    $this->assertDatabaseHas('views',[
        'link_id' => 'YUx021', 
        'ip' => '192.168.0.100',
        'browser' => 'Chrome',
        'os' => 'Windows',
        'access_at' => '2020-01-01 00:00:00',
        'countryISO' => 'BR',
        'countrySubdivisionISO' => 'RS'
    ]);
});