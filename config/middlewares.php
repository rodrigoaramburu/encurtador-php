<?php

declare(strict_types=1);

use App\Middlewares\CorsMiddleware;
use App\Middlewares\ErrorMiddleware;
use Slim\App;

return static function (App $app): void {
    $app->add($app->getContainer()->get(ErrorMiddleware::class));
    $app->add($app->getContainer()->get(CorsMiddleware::class));
};
