<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exception\GenerateLinkException;
use App\Exception\IdExistsException;
use App\Model\Link;
use App\Repository\LinkRepositoryInterface;
use App\Util\GeneratorId;

final class GenerateLink implements GenerateLinkInterface
{
    private const MAX_TRIES = 10;
    private int $tries = 0;

    public function __construct(
        private LinkRepositoryInterface $linkRepository
    ) {
    }

    public function execute(string $url, ?string $id = null): ?Link
    {
        $linkModel = new Link(
            id: $id ?? GeneratorId::generate(),
            url: $url
        );

        try {
            $this->linkRepository->save($linkModel);
        } catch (IdExistsException $e) {
            if ($id) {
                throw $e;
            }

            $this->tries++;
            if ($this->tries >= self::MAX_TRIES) {
                throw new GenerateLinkException('Não foi possível gerar Link');
            }

            return $this->execute(url: $url);
        }

        return $linkModel;
    }
}
