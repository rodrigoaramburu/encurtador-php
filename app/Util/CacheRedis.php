<?php

declare(strict_types=1);

namespace App\Util;

final class CacheRedis implements CacheInterface
{
    public function __construct(
        private \Redis $redis
    ) {
    }

    public function get(string $key): string|false
    {
        return $this->redis->get($key);
    }

    public function set(string $key, string $value, int $expires = -1): void
    {
        $this->redis->set($key, $value);
        if ($expires > 0) {
            $this->redis->expire($key, $expires);
        }
    }
}
