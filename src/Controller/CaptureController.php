<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Capture\Domain\Command\AddWeatherReport;
use App\Capture\Domain\Model\Location;
use App\Capture\Domain\Model\Pressure;
use App\Capture\Domain\Model\ReportDate;
use App\ServiceBus\CommandBus;

final class CaptureController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function report(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            return new Response('', 400);
        }

        $command = new AddWeatherReport();
        $command->location = Location::fromGpsCoordinates(
            $data['location']['latitude'],
            $data['location']['longitude']
        );
        $command->pressure = Pressure::fromFloat($data['pressure']);
        $command->measuredOn = ReportDate::fromString($data['date']);

        $this->commandBus->dispatch($command);

        return new Response('', 201);
    }
}
