<?php
declare(strict_types=1);

namespace App\ServiceBus;

interface QueryBus
{
    public function dispatch(object $query): array;
}
