<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\View;

final class ViewRepositoryPDO implements ViewRepositoryInterface
{
    public function __construct(
        private \PDO $conn
    ) {
    }

    public function save(View $view): void
    {
        $this->conn->prepare('INSERT INTO views (link_id, ip, browser, os, countryISO, countrySubdivisionISO , access_at) VALUES (:link_id, :ip, :browser, :os, :countryISO, :countrySubdivisionISO, :access_at)')
            ->execute([
                'link_id' => $view->id(),
                'ip' => $view->ip(),
                'browser' => $view->browser(),
                'os' => $view->os(),
                'countryISO' => $view->countryISO(),
                'countrySubdivisionISO' => $view->countrySubdivisionISO(),
                'access_at' => $view->accessAt()->format('Y-m-d H:i:s'),
            ]);
    }
}
