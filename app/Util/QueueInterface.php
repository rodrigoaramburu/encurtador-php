<?php

declare(strict_types=1);

namespace App\Util;

interface QueueInterface
{
    public function queue(string $message): void;
    public function dequeue(): string|bool;
}
