<?php

namespace App\Controller\Admin;

use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(CommandeRepository $commandeRepository): Response
    {

        return $this->render('admin/dashboard/index.html.twig', [
            'commandes' => [
                "nouvelles"=>$commandeRepository->findByStatus('Nouvelle'),
                "preparation"=>$commandeRepository->findByStatus('En cours de préparation'),
                "pretes"=>$commandeRepository->findByStatus('Prête'),
                "envoyes"=>$commandeRepository->findByStatus('Envoyée'),
            ],
        ]);
    }
}
