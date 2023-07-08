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

    /**
     * Redirect NotFound media and thumbnails to another environment
     */
    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (
            $exception instanceof NotFoundHttpException
            && (
                strpos($_SERVER['REQUEST_URI'], '/media/') !== false
                || strpos($_SERVER['REQUEST_URI'], '/thumbnail/') !== false
            )
        ) {
            if(empty($_ENV['MEDIA_URL'])){
                return;
            }
            
            header('Location: ' . $_ENV['MEDIA_URL'] . $_SERVER['REQUEST_URI']);
            die;
        }
    }
}