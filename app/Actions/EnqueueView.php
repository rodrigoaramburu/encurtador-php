<?php

declare(strict_types=1);

namespace App\Actions;

use App\Util\QueueInterface;
use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;

final class EnqueueView implements EnqueueViewInterface
{
    public function __construct(
        private QueueInterface $queue,
    ) {
    }

    public function execute(string $id, Request $request): void
    {
        $this->queue->queue(json_encode([
            'id' => $id,
            'ip' => $this->ip($request),
            'user-agent' => $request->getHeaderLine('User-Agent'),
            'access_at' => (new DateTime('now'))->format('Y-m-d H:i:s'),
        ]));
    }

    private function ip(Request $request): ?string
    {
        $serverParams = $request->getServerParams();
        foreach ([
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ] as $key) {
            if (array_key_exists($key, $serverParams)) {
                return $serverParams[$key];
            }
        }
        return null;
    }
}
