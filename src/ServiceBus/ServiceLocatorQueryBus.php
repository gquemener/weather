<?php
declare(strict_types=1);

namespace App\ServiceBus;

use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use App\Capture\Domain\Query\Last24Hours;
use App\Capture\Domain\Query\Last24HoursFinder;
use App\Capture\Domain\Query\AllReports;
use App\Capture\Domain\Query\AllReportsFinder;

final class ServiceLocatorQueryBus implements QueryBus, ServiceSubscriberInterface
{
    private ContainerInterface $locator;

    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
    }

    public function dispatch(object $query): array
    {
        $queryClass = get_class($query);

        if (!$this->locator->has($queryClass)) {
            throw new \InvalidArgumentException(\sprintf(
                'No finder found for query "%s"',
                get_class($query)
            ));
        }

        return $this->locator->get($queryClass)($query);
    }

    public static function getSubscribedServices()
    {
        return [
            Last24Hours::class => Last24HoursFinder::class,
            AllReports::class => AllReportsFinder::class,
        ];
    }
}
