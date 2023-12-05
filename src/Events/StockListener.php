<?php

namespace App\Events;

use App\Entity\Commande;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Workflow\WorkflowInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Commande::class)]
class StockListener
{

    public function __construct(
        private readonly WorkflowInterface $commandeStatusStateMachine,
        private readonly Security $security
    )
    {
    }

    public function prePersist(Commande $commande, PrePersistEventArgs $args): void
    {
        $commande->setUser($this->security->getUser());

        $now = new DateTimeImmutable('NOW', new \DateTimeZone('Europe/Paris'));
        $commande->setDate($now);
        $commande->setDateMaj($now);

        $this->commandeStatusStateMachine->getMarking($commande);
        $commande->getProduit()->updateStock($commande->getQuantite());
        $commande->calculateTotal();
    }
}