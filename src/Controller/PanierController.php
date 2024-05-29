<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Plat;
use Doctrine\ORM\EntityManagerInterface;

class PanierController extends AbstractController
{
    #[Route('/panier/ajout/{id}', name: 'panier_ajout')]
    public function ajout(int $id, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $plat = $entityManager->getRepository(Plat::class)->find($id);
        if (!$plat) {
            throw $this->createNotFoundException('Plat non trouvé');
        }

        $panier = $session->get('panier', []);
        if (!isset($panier[$id])) {
            $panier[$id] = 0;
        }
        $panier[$id]++;
        $session->set('panier', $panier);

        return $this->redirectToRoute('panier_contenu');
    }

    #[Route('/panier', name: 'panier_contenu')]
    public function contenu(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $panier = $session->get('panier', []);
        $panierData = [];

        foreach ($panier as $id => $quantity) {
            $plat = $entityManager->getRepository(Plat::class)->find($id);
            if ($plat) {
                $panierData[] = [
                    'plat' => $plat,
                    'quantity' => $quantity
                ];
            }
        }

        return $this->render('panier/index.html.twig', [
            'panier' => $panierData,
        ]);
    }

    #[Route('/panier/modifier/{id}', name: 'panier_modifier', methods: ['POST'])]
    public function modifier(int $id, Request $request, SessionInterface $session): Response
    {
        $quantite = $request->request->get('quantite');

        if ($quantite <= 0) {
            // Supprimer le plat du panier s'il n'y a pas de quantité
            $this->supprimer($id, $session);
        } else {
            // Mettre à jour la quantité du plat dans le panier
            $panier = $session->get('panier', []);
            $panier[$id] = $quantite;
            $session->set('panier', $panier);
        }

        return $this->redirectToRoute('panier_contenu');
    }

    #[Route('/panier/supprimer/{id}', name: 'panier_supprimer', methods: ['POST'])]
    public function supprimer(int $id, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        unset($panier[$id]);
        $session->set('panier', $panier);

        return $this->redirectToRoute('panier_contenu');
    }
}
