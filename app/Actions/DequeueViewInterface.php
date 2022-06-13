<?php

declare(strict_types=1);

namespace App\Actions;

use App\Model\View;

interface DequeueViewInterface
{
    public function execute(): ?View;
}
