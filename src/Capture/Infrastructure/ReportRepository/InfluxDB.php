<?php
declare(strict_types=1);

namespace App\Capture\Infrastructure\ReportRepository;

use App\Capture\Domain\Model\ReportRepository;
use App\Capture\Domain\Model\Report;
use InfluxDB2\Client;
use InfluxDB2\Model\WritePrecision;
use InfluxDB2\Point;

final class InfluxDB implements ReportRepository
{
    private string $org;
    private string $bucket;
    private Client $client;

    public function __construct(
        string $token,
        string $org,
        string $bucket,
        string $url
    ) {
        $this->org = $org;
        $this->bucket = $bucket;
        $this->client = new Client([
            "url" => $url,
            "token" => $token,
        ]);
    }

    public function add(Report $report): void
    {
        $writeApi = $this->client->createWriteApi();

        $report = $report->toArray();
        $point = Point::measurement('atmospheric_pressure')
            ->addTag('city', 'Nantes')
            ->addField('pressure', $report['pressure'])
            ->time($report['date']);

        $writeApi->write($point, WritePrecision::S, $this->bucket, $this->org);
    }
}
