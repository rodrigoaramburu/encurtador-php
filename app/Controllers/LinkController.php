<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Actions\EnqueueViewInterface;
use App\Actions\GenerateLinkInterface;
use App\Actions\RetrieveLinkInterface;
use App\Actions\StatisticsViewInterface;
use App\Exception\GenerateLinkException;
use App\Exception\IdExistsException;
use App\Exception\LinkNotFoundException;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class LinkController
{
    public function __construct(
        private GenerateLinkInterface $generateLink,
        private RetrieveLinkInterface $retrieveLink,
        private EnqueueViewInterface $queueView,
        private StatisticsViewInterface $statisticsView
    ) {
    }

    public function encurtar(Request $request, Response $response): Response
    {
        $jsonData = json_decode((string) $request->getBody(), true);
        $url = $jsonData['url'] ?? null;
        $id = $jsonData['id'] ?? null;

        if ($url === null) {
            $response->getBody()->write(json_encode([
                'message' => 'URL não informada',
                'code' => 400,
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $link = $this->generateLink->execute(id: $id, url: $url);
        } catch (IdExistsException|GenerateLinkException $e) {
            return $this->errorResponse($e, $response);
        }

        $response->getBody()->write(json_encode([
            'id' => $link->id(),
            'url' => $link->url(),
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function redirect(Request $request, Response $response, array $params): Response
    {
        try {
            $link = $this->retrieveLink->execute(id: $params['id']);
            $this->queueView->execute(id: $params['id'], request: $request);
        } catch (LinkNotFoundException $e) {
            return $this->errorResponse($e, $response);
        }

        return $response->withHeader('Location', $link->url())->withStatus(301);
    }

    public function statistics(Request $request, Response $response, array $params = []): Response
    {
        $response->getBody()->write(json_encode(
            $this->statisticsView->execute(id: $params['id']),
        ));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    private function errorResponse(Exception $e, Response $response): Response
    {
        $response->getBody()->write(json_encode([
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getCode());
    }
}
