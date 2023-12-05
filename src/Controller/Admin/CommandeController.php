<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/admin/commande')]
class CommandeController extends AbstractController
{

    public function __construct(
        // Symfony will inject the 'blog_publishing' workflow configured before
        private readonly WorkflowInterface $commandeStatusStateMachine,
        private readonly EntityManagerInterface $manager
    ) {
    }

    #[Route('/{id}', name: 'app_admin_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('admin/commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/{targetStatus}', name: 'app_admin_commande_workflow')]
    public function workflow(Commande $commande,string $targetStatus): RedirectResponse
    {
        if($this->commandeStatusStateMachine->can($commande,$targetStatus)){
            $this->commandeStatusStateMachine->apply($commande,$targetStatus);
            $commande->updateDateMaj();
            $this->manager->persist($commande);
            $this->manager->flush();
        }

        return $this->redirectToRoute('app_admin_dashboard');
    }

}
