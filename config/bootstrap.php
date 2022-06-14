<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

 return static function () {
     $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
     $dotenv->load();

     $containerBuilder = new ContainerBuilder();

     $containerBuilder->addDefinitions(__DIR__ . '/dependencies.php');

     $container = $containerBuilder->build();

     AppFactory::setContainer($container);
     $app = AppFactory::create();
     $app->addBodyParsingMiddleware();
     
     (require __DIR__ . '/routes.php')($app);
     
     (require __DIR__ . '/middlewares.php')($app);
     
     return $app;
 };
