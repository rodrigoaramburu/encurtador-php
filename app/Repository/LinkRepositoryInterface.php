<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Link;

interface LinkRepositoryInterface
{
    public function save(Link $link): void;
    public function find(string $id): ?Link;
}
