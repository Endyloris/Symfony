<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PlatRepository;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;

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
    public function process(int $id, PlatRepository $platRepository, EntityManagerInterface $entityManager): Response
    {
        $plat = $platRepository->find($id);

        if (!$plat) {
            throw $this->createNotFoundException('Le plat n\'existe pas.');
        }

        $commande = new Commande();
        $commande->setDateCommande(new \DateTime())
                 ->setTotal($plat->getPrix())
                 ->setEtat(0) // Par exemple, 0 pour "Enregistré"
                 ->setUtilisateur($this->getUser()) // Assurez-vous que l'utilisateur est connecté
                 ->addPlat($plat);

        $entityManager->persist($commande);
        $entityManager->flush();

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
