<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\ServiceBus\QueryBus;
use App\Capture\Domain\Query\AllReports;
use Symfony\Component\HttpFoundation\Request;

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

    public function query(Request $request, QueryBus $queryBus): JsonResponse
    {
        $toTimestamp = function(string $datetime): int
        {
            return (\DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $datetime))
                ->setTimezone(new \DateTimeZone('UTC'))
                ->getTimestamp()
            ;
        };

        $data = $request->request->all();
        $from = $toTimestamp($data['range']['from']);
        $to = $toTimestamp($data['range']['to']);

        $timeseries = [
            [
                'target' => 'Nantes, France',
                'datapoints' => array_map(
                    fn(array $data): array => [
                        $data['pressure'],
                        $data['date'] * 1000
                    ],
                    $queryBus->dispatch(AllReports::withPayload(['from' => $from, 'to' => $to]))
                ),
            ]
        ];

        return new JsonResponse($timeseries, 200);
    }
}
