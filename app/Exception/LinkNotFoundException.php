<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class LinkNotFoundException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}