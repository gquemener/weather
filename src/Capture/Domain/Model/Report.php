<?php
declare(strict_types=1);

namespace App\Capture\Domain\Model;

final class Report
{
    private ReportId $id;
    private Pressure $pressure;
    private ReportDate $measuredOn;

    private function __construct(
        ReportId $id,
        ReportDate $measuredOn,
        Pressure $pressure
    ) {
        $this->id = $id;
        $this->measuredOn = $measuredOn;
        $this->pressure = $pressure;
    }

    public static function capture(
        ReportDate $measuredOn,
        Pressure $pressure
    ): self {
        return new self(ReportId::generate(), $measuredOn, $pressure);
    }

    public function id(): ReportId
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'date' => $this->measuredOn->toTimestamp(),
            'pressure' => $this->pressure->value(),
        ];
    }
}
