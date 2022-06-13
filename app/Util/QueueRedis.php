<?php

declare(strict_types=1);

namespace App\Util;

final class QueueRedis implements QueueInterface
{
    public function __construct(
        private \Redis $redis
    ) {
    }

    public function queue(string $message): void
    {
        $this->redis->rpush('queue_view', $message);
    }
    public function dequeue(): string|bool
    {
        return $this->redis->lpop('queue_view');
    }
}
