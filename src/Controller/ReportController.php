<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Capture\Domain\Command\AddWeatherReport;
use App\Capture\Domain\Model\Pressure;
use App\Capture\Domain\Model\ReportDate;
use App\ServiceBus\CommandBus;
use App\ServiceBus\QueryBus;
use App\Capture\Domain\Query\Last24Hours;

final class ReportController
{
    public function add(Request $request, CommandBus $commandBus): Response
    {
        $data = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            return new Response('', 400);
        }

        $command = new AddWeatherReport();
        $command->pressure = Pressure::fromFloat($data['pressure']);
        $command->measuredOn = ReportDate::fromString($data['date']);

        $commandBus->dispatch($command);

        return new Response('', 201);
    }

    public function list(QueryBus $queryBus): Response
    {
        $reports = $queryBus->dispatch(new Last24Hours());

        return new JsonResponse($reports);
    }
}
