<?php

declare(strict_types=1);

use App\Actions\DequeueViewInterface;
use App\Actions\EnqueueView;
use App\Actions\EnqueueViewInterface;
use App\Actions\GenerateLink;
use App\Actions\GenerateLinkInterface;
use App\Actions\RetrieveLink;
use App\Actions\RetrieveLinkInterface;
use App\Actions\StatisticsView;
use App\Actions\StatisticsViewInterface;
use App\Repository\LinkRepositoryInterface;
use App\Repository\LinkRepositoryPDO;
use App\Repository\ViewRepositoryInterface;
use App\Repository\ViewRepositoryPDO;
use App\Util\CacheInterface;
use App\Util\CacheRedis;
use App\Util\QueueInterface;
use App\Util\QueueRedis;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

return [
    PDO::class => DI\factory(static function () {
        return new \PDO(
            'mysql:host='.getenv('DBHOST').';dbname='.getenv('DB'),
            getenv('DBUSER'),
            getenv('DBPASSWORD')
        );
    }),

    Redis::class => DI\factory(static function () {
        $redis = new \Redis();
        $redis->connect(
            getenv('REDIS_HOST'),
            (int) getenv('REDIS_PORT')
        );
        return $redis;
    }),

    Logger::class => DI\factory(static function () {
        $logger = new Logger('log');
        $formatter = new LineFormatter(
            null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
            null, // Datetime format
            true, // allowInlineLineBreaks option, default false
            true  // ignoreEmptyContextAndExtra option, default false
        );

        $stream = new StreamHandler(__DIR__ . '/../logs/encurtador.log', Logger::DEBUG);
        $stream->setFormatter($formatter);
        $logger->pushHandler($stream);

        return $logger;
    }),

    LinkRepositoryInterface::class => \DI\autowire(LinkRepositoryPDO::class),
    ViewRepositoryInterface::class => \DI\autowire(ViewRepositoryPDO::class),
    CacheInterface::class => \DI\autowire(CacheRedis::class),
    QueueInterface::class => \DI\autowire(QueueRedis::class),

    RetrieveLinkInterface::class => \DI\autowire(RetrieveLink::class),
    GenerateLinkInterface::class => \DI\autowire(GenerateLink::class),
    EnqueueViewInterface::class => \DI\autowire(EnqueueView::class),
    DequeueViewInterface::class => \DI\autowire(DequeueView::class),
    StatisticsViewInterface::class => \DI\autowire(StatisticsView::class),

];
