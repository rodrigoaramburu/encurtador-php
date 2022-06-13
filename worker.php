<?php

declare(strict_types=1);

require './vendor/autoload.php';

use App\Actions\DequeueView;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/config/dependencies.php');
$container = $containerBuilder->build();

$dequeue = $container->get(DequeueView::class);

while (true) {
    try {
        $view = $dequeue->execute();

        if ($view === null) {
            sleep(1);
        } else {
            echo "Job executed: link_id {$view->id()} - {$view->accessAt()->format('Y-m-d H:i:s')}" . PHP_EOL;
        }
    } catch (\Exception $e) {
        echo 'Error ao processar um job' . PHP_EOL;
        echo $e->getMessage() . PHP_EOL;
    }
}
