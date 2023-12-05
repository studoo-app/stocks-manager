<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/commandes')]
class CommandeController extends AbstractController
{
    public function __construct(
        // Symfony will inject the 'blog_publishing' workflow configured before
        private readonly WorkflowInterface $commandeStatusStateMachine,
        private readonly EntityManagerInterface $manager
    ) {
    }

    #[Route('/nouvelle', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $commande = new Commande();
//        $commande->setUser($this->getUser());

//        $now = new \DateTimeImmutable('NOW', new \DateTimeZone('Europe/Paris'));
//        $commande->setDate($now);
//        $commande->setDateMaj($now);

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $this->commandeStatusStateMachine->getMarking($commande);
//            $commande->calculateTotal();
//            $commande->getProduit()->setStock($commande->getProduit()->getStock() - $commande->getQuantite());
            $this->manager->persist($commande);
            $this->manager->flush();

            $this->addFlash("success","Commande validée avec succès !");

            return $this->redirectToRoute('app_commande_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

}
