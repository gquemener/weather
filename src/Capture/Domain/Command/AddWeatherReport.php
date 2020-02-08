<?php
declare(strict_types=1);

namespace App\Capture\Domain\Command;

use App\Capture\Domain\Model\Location;
use App\Capture\Domain\Model\Pressure;
use App\Capture\Domain\Model\ReportDate;

final class AddWeatherReport
{
    public Location $location;
    public Pressure $pressure;
    public ReportDate $measuredOn;
}
