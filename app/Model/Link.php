<?php

declare(strict_types=1);

namespace App\Model;

class Link
{
    public function __construct(
        private string $id,
        private string $url,
    ){   
    }

    public function id(): string
    {
        return $this->id;
    }
    public function url(): string
    {
        return $this->url;
    }

}