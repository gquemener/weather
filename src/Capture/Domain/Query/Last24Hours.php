<?php
declare(strict_types=1);

namespace App\Capture\Domain\Query;

final class Last24Hours
{
    public static function withPayload(array $data): self
    {
        return new self();
    }
}
