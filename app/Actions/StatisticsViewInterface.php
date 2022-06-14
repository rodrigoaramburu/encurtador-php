<?php

declare(strict_types=1);

namespace App\Actions;

interface StatisticsViewInterface
{
    public function execute(string $id): array;
}
