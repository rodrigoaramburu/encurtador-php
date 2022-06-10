<?php

use DI\Container;
use Dotenv\Dotenv;
use App\Util\CacheRedis;
use App\Util\CacheInterface;
use Slim\Factory\AppFactory;
use App\Repository\LinkRepositoryPDO;
use App\Repository\LinkRepositoryInterface;


return function(){

    $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
    $dotenv->load();


    $container = new  Container();
    
    $container->set( PDO::class , function(){
        $con = new PDO("mysql:host=".getenv('DBHOST').";dbname=".getenv('DB'), getenv('DBUSER'), getenv('DBPASSWORD'));
        return $con;
    });

    $container->set(Redis::class, function(){
        $redis = new Redis();
        $redis->connect(getenv('REDIS_HOST'), getenv('REDIS_PORT'));
        return $redis;
    });

    $container->set(LinkRepositoryInterface::class, \DI\autowire(LinkRepositoryPDO::class));
    $container->set(CacheInterface::class, \DI\autowire(CacheRedis::class));
    
    AppFactory::setContainer($container);
    $app = AppFactory::create();
    $app->addBodyParsingMiddleware();
    return $app;
};