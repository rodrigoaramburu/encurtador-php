<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\IdExistsException;
use App\Model\Link;
use PDO;

final class LinkRepositoryPDO implements LinkRepositoryInterface
{
    public function __construct(
        private PDO $conn
    ) {
    }

    public function save(Link $link): void
    {
        try {
            $this->conn->prepare('INSERT INTO links (id, url) VALUES (:id, :url)')
                ->execute([
                    'id' => $link->id(),
                    'url' => $link->url(),
                ]);
        } catch (\PDOException $e) {
            throw new IdExistsException('Id já existe');
        }
    }
    public function find(string $id): ?Link
    {
        $query = $this->conn->prepare('SELECT * FROM links WHERE id = :id');
        $query->execute(['id' => $id]);
        $data = $query->fetch();

        if (! $data) {
            return null;
        }

        return new Link(
            id: $data['id'],
            url: $data['url']
        );
    }
}
