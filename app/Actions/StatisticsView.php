<?php

declare(strict_types=1);

namespace App\Actions;

use App\Repository\ViewRepositoryInterface;

final class StatisticsView implements StatisticsViewInterface
{
    public function __construct(
        private ViewRepositoryInterface $viewRepository
    ) {
    }
    public function execute(string $id): array
    {
        return [
            'total' => $this->viewRepository->statisticsTotal($id),
            'browsers' => $this->viewRepository->statisticsBrowser($id),
            'os' => $this->viewRepository->statisticsOs($id),
            'countries' => $this->viewRepository->statisticsCountry($id),
        ];
    }
}
