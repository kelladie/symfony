<?php

namespace App\EventSubscriber;

use App\Event\AddPersonneEvent;
use App\Service\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class PersonneEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private MailerService $mail)
    {
    }
    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
            AddPersonneEvent::ADD_PERSONNE_EVENT => [
                ['onAddPersonneEvent', 5000]
            ],
        ];
    }
    public function onAddPersonneEvent(AddPersonneEvent $event)
    {
        $personne = $event->getPersonne();
        $mailMessage = $personne->getFirstName() . ' ' . $personne->getName() . ' ' . "a bien été ajouté avec success";
        $this->mail->sendEmail(content: $mailMessage, subject: 'Mail send from eventSubscriber');
    }
}
