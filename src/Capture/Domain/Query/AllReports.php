<?php
declare(strict_types=1);

namespace App\Capture\Domain\Query;

final class AllReports
{
    public int $from;
    public int $to;

    public static function withPayload(array $data): self
    {
        $self = new self();
        $self->from = $data['from'];
        $self->to = $data['to'];

        return $self;
    }
}
