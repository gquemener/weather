<?php
declare(strict_types=1);

namespace App\Capture\Domain\Query;

use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\DocumentSnapshot;
use App\Capture\Domain\Model\ReportDate;

final class AllReportsFinder
{
    private FirestoreClient $firestore;

    public function __construct(FirestoreClient $firestore)
    {
        $this->firestore = $firestore;
    }

    public function __invoke(AllReports $query): array
    {
        return array_map(
            fn(DocumentSnapshot $snapshot) => $snapshot->data(),
            iterator_to_array(
                $this->firestore
                     ->collection('reports')
                     ->where('date', '>=', $query->from)
                     ->where('date', '<=', $query->to)
                     ->orderBy('date', 'ASC')
                     ->documents()
            )
        );
    }
}
