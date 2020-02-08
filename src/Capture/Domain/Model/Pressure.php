<?php
declare(strict_types=1);

namespace App\Capture\Domain\Model;

final class Pressure
{
    private int $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function fromInteger(int $value): self
    {
        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }
}
