<?php
declare(strict_types=1);

namespace App\Capture\Domain\Command;

use App\Capture\Domain\Model\Pressure;
use App\Capture\Domain\Model\ReportDate;

final class AddWeatherReport
{
    public Pressure $pressure;
    public ReportDate $measuredOn;

    public static function withPayload(array $data): self
    {
        $self = new self();
        $self->pressure = Pressure::fromFloat($data['pressure']);
        $self->measuredOn = ReportDate::fromTimestamp($data['date']);

        return $self;
    }
}
