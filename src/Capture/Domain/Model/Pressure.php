<?php
declare(strict_types=1);

namespace App\Capture\Domain\Model;

final class Pressure
{
    private float $value;

    private function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function fromFloat(float $value): self
    {
        return new self($value);
    }

    public function value(): float
    {
        return $this->value;
    }
}
