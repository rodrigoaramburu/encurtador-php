<?php

declare(strict_types=1);

use App\Controllers\LinkController;
use Slim\App;
use Slim\Exception\HttpNotFoundException;

return static function (App $app): void {
    $app->post('/api/encurtar', [LinkController::class,'encurtar']);

    $app->get('/api/statistics/{id}', [LinkController::class,'statistics']);

    $app->get('/{id}', [LinkController::class,'redirect']);

    // cors
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response): void {
        throw new HttpNotFoundException($request);
    });
};
