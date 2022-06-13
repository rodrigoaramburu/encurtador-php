<?php

declare(strict_types=1);

namespace App\Actions;

use Psr\Http\Message\ServerRequestInterface as Request;

interface EnqueueViewInterface
{
    public function execute(string $id, Request $request): void;
}
