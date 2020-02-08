<?php
declare(strict_types=1);

namespace App\Capture\Domain\Model;

final class Report
{
    private ReportId $id;
    private Location $location;
    private Pressure $pressure;
    private ReportDate $measuredOn;

    private function __construct(
        ReportId $id,
        Location $location,
        ReportDate $measuredOn,
        Pressure $pressure
    ) {
        $this->id = $id;
        $this->location = $location;
        $this->measuredOn = $measuredOn;
        $this->pressure = $pressure;
    }

    public static function capture(
        Location $location,
        ReportDate $measuredOn,
        Pressure $pressure
    ): self {
        return new self(ReportId::generate(), $location, $measuredOn, $pressure);
    }

    public function id(): ReportId
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'location' => [
                'latitude' => $this->location->latitude(),
                'longitude' => $this->location->longitude(),
            ],
            'date' => $this->measuredOn->toString(),
            'pressure' => $this->pressure->value(),
        ];
    }
}
