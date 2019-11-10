<?php
namespace App\Event;
use App\Event\UserRegisterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
class UserSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }
    public function onUserRegister(UserRegisterEvent $event)
    {
        // $event->getRegisteredUser()
    }
}