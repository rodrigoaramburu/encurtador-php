<?php

declare(strict_types=1);

namespace App\Util;

interface CacheInterface
{
    public function get(string $key): string|false;
    public function set(string $key, string $value, int $expires = -1): void;
}