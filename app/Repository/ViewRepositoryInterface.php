<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\View;

interface ViewRepositoryInterface
{
    public function save(View $statistics): void;
}
