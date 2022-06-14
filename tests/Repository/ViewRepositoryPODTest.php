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



test('deve retornar estatísticas de total de visualizações de link encurtado', function(){

    $sql = <<<'SQL'
        INSERT INTO `views` (`id`, `link_id`, `ip`, `browser`, `os`, `countryISO`, `countrySubdivisionISO`, `access_at`) VALUES
        (14852, 'BOTECO', '189.7.172.169', 'Chrome', 'Win10', 'BR', 'RS', '2022-06-14 12:52:39'),
        (14853, 'BOTECO', '189.7.172.169', 'Firefox', 'Win10', 'US', 'MN', '2022-06-14 12:52:39'),
        (14854, 'BOTECO', '189.7.172.169', 'Firefox', 'Linux', 'BR', 'SP', '2022-06-14 12:52:39'),
        (14855, 'BOTECO', '189.7.172.169', 'Edge', 'Win10', 'BR', 'MG', '2022-06-14 12:52:39'),
        (14856, 'YUf5F1', '189.7.172.169', 'Chrome', 'Win10', 'BR', 'MG', '2022-06-14 12:52:39');
    SQL;

    $this->pdoConn()->exec($sql);

    $result = $this->repository->statisticsTotal('BOTECO');

    expect($result)->toBe(4);

});

test('deve retornar estatísticas de total por browser', function(){
    $sql = <<<'SQL'
        INSERT INTO `views` (`id`, `link_id`, `ip`, `browser`, `os`, `countryISO`, `countrySubdivisionISO`, `access_at`) VALUES
        (14852, 'BOTECO', '189.7.172.169', 'Chrome', 'Win10', 'BR', 'RS', '2022-06-14 12:52:39'),
        (14853, 'BOTECO', '189.7.172.169', 'Firefox', 'Win10', 'US', 'MN', '2022-06-14 12:52:39'),
        (14854, 'BOTECO', '189.7.172.169', 'Firefox', 'Linux', 'BR', 'SP', '2022-06-14 12:52:39'),
        (14855, 'BOTECO', '189.7.172.169', 'Edge', 'Win10', 'BR', 'MG', '2022-06-14 12:52:39'),
        (14856, 'YUf5F1', '189.7.172.169', 'Chrome', 'Win10', 'BR', 'MG', '2022-06-14 12:52:39');
    SQL;

    $this->pdoConn()->exec($sql);

    $result = $this->repository->statisticsBrowser('BOTECO');

    expect($result)->toMatchArray([
        'Chrome' => 1,
        'Firefox' => 2,
        'Edge' => 1
    ]);

});

test('deve retornar estatísticas de total por os', function(){
    $sql = <<<'SQL'
        INSERT INTO `views` (`id`, `link_id`, `ip`, `browser`, `os`, `countryISO`, `countrySubdivisionISO`, `access_at`) VALUES
        (14852, 'BOTECO', '189.7.172.169', 'Chrome', 'Win10', 'BR', 'RS', '2022-06-14 12:52:39'),
        (14853, 'BOTECO', '189.7.172.169', 'Firefox', 'Win10', 'US', 'MN', '2022-06-14 12:52:39'),
        (14854, 'BOTECO', '189.7.172.169', 'Firefox', 'Linux', 'BR', 'SP', '2022-06-14 12:52:39'),
        (14855, 'BOTECO', '189.7.172.169', 'Edge', 'Win10', 'BR', 'MG', '2022-06-14 12:52:39'),
        (14856, 'YUf5F1', '189.7.172.169', 'Chrome', 'Win10', 'BR', 'MG', '2022-06-14 12:52:39');
    SQL;

    $this->pdoConn()->exec($sql);

    $result = $this->repository->statisticsOs('BOTECO');

    expect($result)->toMatchArray([
        'Win10' => 3,
        'Linux' => 1
    ]);

});

test('deve retornar estatísticas de total por country', function(){
    $sql = <<<'SQL'
        INSERT INTO `views` (`id`, `link_id`, `ip`, `browser`, `os`, `countryISO`, `countrySubdivisionISO`, `access_at`) VALUES
        (14852, 'BOTECO', '189.7.172.169', 'Chrome', 'Win10', 'BR', 'RS', '2022-06-14 12:52:39'),
        (14853, 'BOTECO', '189.7.172.169', 'Firefox', 'Win10', 'US', 'MN', '2022-06-14 12:52:39'),
        (14854, 'BOTECO', '189.7.172.169', 'Firefox', 'Linux', 'BR', 'SP', '2022-06-14 12:52:39'),
        (14855, 'BOTECO', '189.7.172.169', 'Edge', 'Win10', 'BR', 'MG', '2022-06-14 12:52:39'),
        (14856, 'YUf5F1', '189.7.172.169', 'Chrome', 'Win10', 'BR', 'MG', '2022-06-14 12:52:39');
    SQL;

    $this->pdoConn()->exec($sql);

    $result = $this->repository->statisticsCountry('BOTECO');

    expect($result)->toMatchArray([
        'BR' => 3,
        'US' => 1
    ]);

});

test('deve retornar array vazio se não existir registros de visualizaçaões de browser', function(){
    $result = $this->repository->statisticsBrowser('BOTECO');

    expect($result)->toBe([]);
}); 

test('deve retornar array vazio se não existir registros de visualizaçaões de os', function(){
    $result = $this->repository->statisticsOs('BOTECO');

    expect($result)->toBe([]);
}); 

test('deve retornar array vazio se não existir registros de visualizaçaões de country', function(){
    $result = $this->repository->statisticsCountry('BOTECO');

    expect($result)->toBe([]);
}); 