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

    public function statisticsTotal(string $id): int
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) AS total FROM views WHERE link_id = :link_id');
        $stmt->execute(['link_id' => $id]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function statisticsBrowser(string $id): array
    {
        $stmt = $this->conn->prepare('SELECT browser, COUNT(*) AS total FROM views WHERE link_id = :link_id GROUP BY browser');
        $stmt->execute(['link_id' => $id]);

        while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $browsers[$result['browser']] = $result['total'];
        }
        return $browsers;
    }

    public function statisticsOS(string $id): array
    {
        $stmt = $this->conn->prepare('SELECT os, COUNT(*) AS total FROM views WHERE link_id = :link_id GROUP BY os');
        $stmt->execute(['link_id' => $id]);

        while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $os[$result['os']] = $result['total'];
        }

        return $os;
    }

    public function statisticsCountry(string $id): array
    {
        $stmt = $this->conn->prepare('SELECT countryISO, COUNT(*) AS total FROM views WHERE link_id = :link_id GROUP BY countryISO');
        $stmt->execute(['link_id' => $id]);

        while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $country[$result['countryISO']] = $result['total'];
        }

        return $country;
    }
}
