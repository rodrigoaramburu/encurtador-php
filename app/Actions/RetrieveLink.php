<?php 

declare(strict_types=1);

namespace App\Actions;

use App\Model\Link;
use App\Util\CacheInterface;
use App\Exception\LinkNotFoundException;
use App\Repository\LinkRepositoryInterface;

class RetrieveLink
{

    public function __construct(
        private LinkRepositoryInterface $linkRepository,
        private CacheInterface $cache
    ){   
    }

    public function execute(string $id): ?Link
    {
        $url = $this->cache->get($id);

        if($url) return new Link($id, $url);

        $link = $this->linkRepository->find($id);
        
        if($link === null) throw new LinkNotFoundException('Link não encontrado');

        $this->cache->set($id, $link->url());

        return $link;
    }
}