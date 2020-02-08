<?php
declare(strict_types=1);

namespace App\Capture\Domain\Command;

use App\Capture\Domain\Model\ReportRepository;
use App\Capture\Domain\Model\Report;

final class AddWeatherReportHandler
{
    private ReportRepository $repository;

    public function __construct(ReportRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(AddWeatherReport $command): void
    {
        $report = Report::capture(
            $command->location,
            $command->measuredOn,
            $command->pressure
        );

        $this->repository->add($report);
    }
}
