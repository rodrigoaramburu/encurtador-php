<?php 

declare(strict_types=1);

namespace App\Actions;

use App\Model\Link;
use App\Exception\LinkNotFoundException;
use App\Repository\LinkRepositoryInterface;

class RetrieveLink
{

    public function __construct(
        private LinkRepositoryInterface $linkRepository
    ){   
    }

    public function execute(string $id): ?Link
    {
        return $this->linkRepository->find($id) ?? throw new LinkNotFoundException('Link não encontrado');
    }
}