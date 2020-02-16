<?php
declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;

final class JsonBodyListener implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ('json' !== $request->getContentType()) {
            return;
        }

        $content = $request->getContent();
        if (empty($content)) {
            return;
        }

        $data = json_decode($content, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            $event->setResponse(new JsonResponse(
                sprintf('{"message":"%s"}', json_last_error_msg()),
                400,
                [],
                true
            ));
        }

        $request->request = new ParameterBag($data);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
