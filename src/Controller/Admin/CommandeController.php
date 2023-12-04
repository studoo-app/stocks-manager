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

    #[Route('/', name: 'app_admin_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('admin/commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('admin/commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/preparer', name: 'app_admin_commande_to_prepare')]
    public function prepare(Commande $commande): RedirectResponse
    {
        if($this->commandeStatusStateMachine->can($commande,'to_prepare')){
            $this->commandeStatusStateMachine->apply($commande,'to_prepare');
            $this->manager->persist($commande);
            $this->manager->flush();
        }

        return $this->redirectToRoute('app_admin_commande_index',[]);
    }

    #[Route('/{id}/prete', name: 'app_admin_commande_to_ready')]
    public function ready(Commande $commande): RedirectResponse
    {
        if($this->commandeStatusStateMachine->can($commande,'to_ready')){
            $this->commandeStatusStateMachine->apply($commande,'to_ready');
            $this->manager->persist($commande);
            $this->manager->flush();
        }

        return $this->redirectToRoute('app_admin_commande_index',[]);
    }

    #[Route('/{id}/envoyee', name: 'app_admin_commande_to_send')]
    public function send(Commande $commande): RedirectResponse
    {
        if($this->commandeStatusStateMachine->can($commande,'to_send')){
            $this->commandeStatusStateMachine->apply($commande,'to_send');
            $this->manager->persist($commande);
            $this->manager->flush();
        }

        return $this->redirectToRoute('app_admin_commande_index',[]);
    }

}
