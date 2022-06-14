<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exception\LinkNotFoundException;
use App\Repository\LinkRepositoryInterface;
use App\Repository\ViewRepositoryInterface;

final class StatisticsView implements StatisticsViewInterface
{
    public function __construct(
        private ViewRepositoryInterface $viewRepository,
        private LinkRepositoryInterface $linkRepository
    ) {
    }
    public function execute(string $id): array
    {
        $link = $this->linkRepository->find($id);
        if (! $link) {
            throw new LinkNotFoundException('Link not found');
        }
        return [
            'total' => $this->viewRepository->statisticsTotal($id),
            'browsers' => $this->viewRepository->statisticsBrowser($id),
            'os' => $this->viewRepository->statisticsOs($id),
            'countries' => $this->viewRepository->statisticsCountry($id),
        ];
    }
}
