<?php

declare(strict_types=1);

namespace App\Actions;

use App\Model\View;
use App\Repository\ViewRepositoryInterface;
use App\Util\IpLocation;
use App\Util\QueueInterface;

final class DequeueView implements DequeueViewInterface
{
    public function __construct(
        private QueueInterface $queue,
        private ViewRepositoryInterface $viewRepository
    ) {
    }

    public function execute(): ?View
    {
        $json = $this->queue->dequeue();
        if ($json === false) {
            return null;
        }
        $data = json_decode($json, true);

        $browserInfo = get_browser($data['user-agent'], true);
        $locationIso = IpLocation::locationIso($data['ip']);

        $view = new View(
            id: $data['id'],
            ip: $data['ip'],
            accessAt: new \DateTime($data['access_at']),
            browser: $browserInfo['browser'],
            os: $browserInfo['platform'],
            countryISO: $locationIso['country'] ?? 'Unknown',
            countrySubdivisionISO: $locationIso['subdivision'] ?? 'Unknown',
        );
        $this->viewRepository->save($view);
        return $view;
    }
}
