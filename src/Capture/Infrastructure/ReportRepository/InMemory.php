<?php
declare(strict_types=1);

namespace App\Capture\Infrastructure\ReportRepository;

use App\Capture\Domain\Model\ReportRepository;
use App\Capture\Domain\Model\Report;

final class InMemory implements ReportRepository
{
    private array $reports = [];

    public function add(Report $report): void
    {
        $this->reports[] = $report;
        die(var_dump($this->reports));
    }
}
