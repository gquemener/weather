<?php
declare(strict_types=1);

namespace App\Capture\Domain\Model;

use App\Capture\Domain\Model\Exception\InvalidLocation;

final class Location
{
    private float $latitude;
    private float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        if ($latitude < -90.0 || $latitude > 90.0) {
            throw new InvalidLocation(sprintf('Latitude must be between -90.0 and 90.0, got "%s"', $latitude));
        }

        if ($longitude < -180.0 || $longitude > 180.0) {
            throw new InvalidLocation(sprintf('Longitude must be between -180.0 and 180.0, got "%s"', $longitude));
        }

        $this->latitude = $latitude;
        $this->longitude = $longitude;

    }

    public static function fromGpsCoordinates(float $latitude, float $longitude): self
    {
        return new self($latitude, $longitude);
    }

    public function latitude(): float
    {
        return $this->latitude;
    }

    public function longitude(): float
    {
        return $this->longitude;
    }
}
