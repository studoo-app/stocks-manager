<?php

namespace App\Events;

use App\Entity\Commande;
use App\Service\WorkFlowLogService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class CommandeWorkflowSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private readonly WorkFlowLogService $service
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            "workflow.commande_status.completed"=>"onWorkflowPerformed",
        ];
    }

    public function onWorkflowPerformed(Event $event): void
    {
        $event->getSubject()->updateDateMaj();
        $this->service->log(
            $event->getSubject()->getId(),
            $event->getSubject()->getDateMaj(),
            $event->getSubject()->getStatus()
        );
    }

}