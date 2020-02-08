<?php
declare(strict_types=1);

namespace App\Capture\Domain\Model;

use App\Github\Domain\Model\PullRequestId;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

final class ReportId
{
    private UuidInterface $id;

    private function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public function toString(): string
    {
        return $this->id->toString();
    }
}
