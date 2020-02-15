<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\ServiceBus\QueryBus;
use App\Capture\Domain\Query\AllReports;

final class GrafanaController
{
    public function test(): Response
    {
        return new Response('', 200);
    }

    public function search(): JsonResponse
    {
        return new JsonResponse(['Nantes, France'], 200);
    }

    public function query(QueryBus $queryBus): JsonResponse
    {
        $data = [
            [
                'target' => 'Nantes, France',
                'datapoints' => array_map(
                    fn(array $data): array => [
                        $data['pressure'],
                        $data['date'] * 1000
                    ],
                    $queryBus->dispatch(new AllReports())
                ),
            ]
        ];

        return new JsonResponse($data, 200);
    }
}
