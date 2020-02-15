<?php
declare(strict_types=1);

namespace App\Capture\Domain\Query;

use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\DocumentSnapshot;
use App\Capture\Domain\Model\ReportDate;

final class Last24HoursFinder
{
    private FirestoreClient $firestore;

    public function __construct(FirestoreClient $firestore)
    {
        $this->firestore = $firestore;
    }

    public function __invoke(): array
    {
        $reports = array_map(
            function(DocumentSnapshot $snapshot) {
                $data = $snapshot->data();
                $data['date'] = (ReportDate::fromTimestamp($data['date']));

                return $data;
            },
            iterator_to_array(
                $this->firestore
                     ->collection('reports')
                     ->where('date', '>=', time() - 7 * 24 * 60 * 60)
                     ->orderBy('date', 'DESC')
                     ->limit(24)
                     ->documents()
            )
        );

        $data = array_reverse($reports);
        $history = [];
        foreach ($data as $value) {
            $history[$value['date']->hours()] = [
                'date' => $value['date']->toString(),
                'pressure' => $value['pressure'],
            ];
        }

        return array_values($history);
    }
}
