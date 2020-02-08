<?php
declare(strict_types=1);

namespace App\Capture\Domain\Model;

interface ReportRepository
{
    public function add(Report $report): void;
}
