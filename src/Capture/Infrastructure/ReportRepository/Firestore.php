<?php
declare(strict_types=1);

namespace App\Capture\Infrastructure\ReportRepository;

use App\Capture\Domain\Model\ReportRepository;
use App\Capture\Domain\Model\Report;
use Google\Cloud\Firestore\FirestoreClient;

final class Firestore implements ReportRepository
{
    private FirestoreClient $firestore;

    public function __construct(FirestoreClient $firestore)
    {
        $this->firestore = $firestore;
    }

    public function add(Report $report): void
    {
        $this->firestore
             ->collection('reports')
             ->document($report->id()->toString())
             ->set($report->toArray())
        ;
    }
}
