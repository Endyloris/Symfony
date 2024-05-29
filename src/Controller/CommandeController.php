<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PlatRepository;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeRepository;

class CommandeController extends AbstractController
{
    #[Route('/commande/{id}', name: 'commande_index')]
    public function index(int $id, PlatRepository $platRepository): Response
    {
        $plat = $platRepository->find($id);

        if (!$plat) {
            throw $this->createNotFoundException('Le plat n\'existe pas.');
        }

        return $this->render('commande/index.html.twig', [
            'plat' => $plat,
        ]);
    }

    #[Route('/commande/process/{id}', name: 'commande_process', methods: ['POST'])]
    public function process(int $id, PlatRepository $platRepository, CommandeRepository $commandeRepository): Response
    {
        $plat = $platRepository->find($id);

        if (!$plat) {
            throw $this->createNotFoundException('Le plat n\'existe pas.');
        }

        $user = $this->getUser();
        $userId = $user ? $user->getId() : null;

        if (!$userId) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $commande = $commandeRepository->processCommande($plat->getId(), $userId);

        return $this->redirectToRoute('commande_confirmation', ['id' => $commande->getId()]);
    }

    #[Route('/commande/confirmation/{id}', name: 'commande_confirmation')]
    public function confirmation(int $id, EntityManagerInterface $entityManager): Response
    {
        $commande = $entityManager->getRepository(Commande::class)->find($id);

        if (!$commande) {
            throw $this->createNotFoundException('La commande n\'existe pas.');
        }

        return $this->render('commande/confirmation.html.twig', [
            'commande' => $commande,
        ]);
    }
}
