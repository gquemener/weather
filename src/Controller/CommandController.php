<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\ServiceBus\CommandBus;

final class CommandController
{
    public function dispatch(Request $request, CommandBus $commandBus): Response
    {
        $data = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            return new JsonResponse(
                sprintf('{"message":"%s"}', json_last_error_msg()),
                400,
                [],
                true
            );
        }

        if (!array_key_exists('name', $data) || !array_key_exists('payload', $data)) {
            return new JsonResponse(
                '{"message":"Invalid JSON payload"}',
                400,
                [],
                true
            );
        }

        $className = sprintf('App\\Capture\\Domain\\Command\\%s', $data["name"]);

        if (!class_exists($className)) {
            return new JsonResponse(
                sprintf('{"message":"Command \"%s\" is not defined"}', $data["name"]),
                400,
                [],
                true
            );
        }

        $command = $className::withPayload($data["payload"]);

        $commandBus->dispatch($command);

        return new JsonResponse(null, 201);
    }
}
