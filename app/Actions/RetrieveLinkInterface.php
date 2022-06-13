<?php

declare(strict_types=1);

namespace App\Actions;

use App\Model\Link;

interface RetrieveLinkInterface
{
    public function execute(string $id): ?Link;
}
