<?php
declare(strict_types=1);

namespace App\Capture\Domain\Model;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

final class ReportDate
{
    private const FORMAT = DateTimeInterface::ATOM;

    private DateTimeImmutable $datetime;

    private function __construct(DateTimeImmutable $datetime)
    {
        $this->datetime = $datetime;
    }

    public static function fromString(string $data): self
    {
        $datetime = DateTimeImmutable::createFromFormat(self::FORMAT, $data);

        if (false === $datetime) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is not a valid "%s" datetime',
                $data,
                self::FORMAT
            ));
        }

        return new self($datetime);
    }

    public static function fromTimestamp(int $timestamp): self
    {
        $datetime = new \DateTimeImmutable();

        return new self($datetime->setTimestamp($timestamp));
    }

    public function toString(): string
    {
        return $this->datetime->format(self::FORMAT);
    }

    public function toTimestamp(): int
    {
        return $this->datetime->getTimestamp();
    }

    public function hours(): int
    {
        return (int) $this->datetime->format('H');
    }
}
