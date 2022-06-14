<?php

declare(strict_types=1);

use App\Controllers\LinkController;
use Slim\App;

return static function (App $app): void {
    $app->post('/api/encurtar', [LinkController::class,'encurtar']);

    $app->get('/statistics/{id}', [LinkController::class,'statistics']);
    $app->get('/{id}', [LinkController::class,'redirect']);
};
