<?php
declare(strict_types=1);

namespace App\Capture\Domain\Model;

final class Report
{
    private Location $location;
    private Pressure $pressure;
    private ReportDate $measuredOn;

    private function __construct(
        Location $location,
        ReportDate $measuredOn,
        Pressure $pressure
    ) {
        $this->location = $location;
        $this->measuredOn = $measuredOn;
        $this->pressure = $pressure;
    }

    public static function capture(
        Location $location,
        ReportDate $measuredOn,
        Pressure $pressure
    ): self {
        return new self($location, $measuredOn, $pressure);
    }
}
