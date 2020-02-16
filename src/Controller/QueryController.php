<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\ServiceBus\QueryBus;

final class QueryController
{
    public function dispatch(Request $request, QueryBus $queryBus): Response
    {
        $data = $request->request->all();

        if (!array_key_exists('name', $data) || !array_key_exists('payload', $data)) {
            return new JsonResponse(
                '{"message":"Invalid JSON payload"}',
                400,
                [],
                true
            );
        }

        $className = sprintf('App\\Capture\\Domain\\Query\\%s', $data["name"]);

        if (!class_exists($className)) {
            return new JsonResponse(
                sprintf('{"message":"Query \"%s\" is not defined"}', $data["name"]),
                400,
                [],
                true
            );
        }

        $query = $className::withPayload($data["payload"]);

        $result = $queryBus->dispatch($query);

        return new JsonResponse($result, 200);
    }
}
