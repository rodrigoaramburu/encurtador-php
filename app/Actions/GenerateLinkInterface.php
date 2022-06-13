<?php

declare(strict_types=1);

namespace App\Actions;

use App\Model\Link;

interface GenerateLinkInterface
{
    public function execute(string $url, ?string $id = null): ?Link;
}
