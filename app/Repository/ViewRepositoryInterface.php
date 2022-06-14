<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\View;

interface ViewRepositoryInterface
{
    public function save(View $statistics): void;

    public function statisticsTotal(string $id): int;

    public function statisticsBrowser(string $id): array;

    public function statisticsOS(string $id): array;

    public function statisticsCountry(string $id): array;
}
