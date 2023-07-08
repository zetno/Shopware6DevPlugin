<?php declare(strict_types=1);

namespace Dev\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    
    public static function getSubscribedEvents(): array
    {
        // Return the events to listen to as array like this:  <event to listen to> => <method to execute>
        return [
            KernelEvents::EXCEPTION => 'onException'
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if( $exception instanceof NotFoundHttpException && strpos($_SERVER['REQUEST_URI'], '/media/') !== false){
            header('Location: https://www.knipidee.nl'.$_SERVER['REQUEST_URI']);
        }
    }
}