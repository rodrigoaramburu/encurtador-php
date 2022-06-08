<?php 

declare(strict_types=1);

use Slim\App;
use App\Controllers\LinkController;

return function(App $app) {
    $app->post('/api/encurtar' , [LinkController::class,'encurtar']);

    $app->get('/{id}' , [LinkController::class,'redirect']);
};