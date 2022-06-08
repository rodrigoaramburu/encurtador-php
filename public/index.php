<?php 

declare(strict_types=1);

require '../vendor/autoload.php';
 
$bootstrap = require(__DIR__ . '/../app/bootstrap.php');
$app = $bootstrap();

$errorMiddleware = require(__DIR__.'/../app/middleware/error.php');
$errorMiddleware($app);

$routes = require(__DIR__ . '/../app/routes.php');
$routes($app);

$app->run();
