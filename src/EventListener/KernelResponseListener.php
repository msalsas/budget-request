<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class KernelResponseListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $httpRequestOrigin = $event->getRequest()->headers->get('origin');

        $event->getResponse()->headers->set('Access-Control-Allow-Origin', $httpRequestOrigin);
        $event->getResponse()->headers->set('Access-Control-Allow-Credentials', 'true');
    }
}