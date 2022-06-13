<?php

declare(strict_types=1);

namespace App\Model;

final class View
{
    public function __construct(
        private string $id,
        private string $ip,
        private \DateTime $accessAt,
        private string $browser,
        private string $os,
        private string $countryISO,
        private string $countrySubdivisionISO
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function ip(): string
    {
        return $this->ip;
    }

    public function accessAt(): \DateTime
    {
        return $this->accessAt;
    }

    public function browser(): string
    {
        return $this->browser;
    }

    public function os(): string
    {
        return $this->os;
    }

    public function countryISO(): string
    {
        return $this->countryISO;
    }

    public function countrySubdivisionISO(): string
    {
        return $this->countrySubdivisionISO;
    }
}
