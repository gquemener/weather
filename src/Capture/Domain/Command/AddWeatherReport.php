<?php
declare(strict_types=1);

namespace App\Capture\Domain\Command;

use App\Capture\Domain\Model\Pressure;
use App\Capture\Domain\Model\ReportDate;

final class AddWeatherReport
{
    public Pressure $pressure;
    public ReportDate $measuredOn;
}
