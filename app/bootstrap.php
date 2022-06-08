<?php

use DI\Container;
use Dotenv\Dotenv;
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

    $container->set(LinkRepositoryInterface::class, \DI\autowire(LinkRepositoryPDO::class));
    
    AppFactory::setContainer($container);
    $app = AppFactory::create();
    $app->addBodyParsingMiddleware();
    return $app;
};