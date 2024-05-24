<?php

// src/Controller/CatalogueController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; // Importez l'EntityManagerInterface
use App\Entity\Categorie;
use App\Entity\Plat;

class CatalogueController extends AbstractController
{
    #[Route('/', name: 'catalogue_home')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response // Injection de la classe Request et de EntityManagerInterface
    {
        $searchTerm = $request->query->get('search');
        $plats = [];

        if ($searchTerm) {
            $plats = $entityManager->getRepository(Plat::class)->searchPlats($searchTerm); // Utilisez l'entityManager pour obtenir le repository
        } else {
            $plats = $entityManager->getRepository(Plat::class)->findAll();
        }

        return $this->render('catalogue/index.html.twig', [
            'plats' => $plats,
            'search' => $searchTerm,
        ]);
    }

    #[Route('/plats', name: 'catalogue_plats')]
    public function plats(EntityManagerInterface $entityManager): Response
    {
        $plats = $entityManager->getRepository(Plat::class)->findAll();
        return $this->render('catalogue/plats.html.twig', [
            'plats' => $plats,
        ]);
    }

    #[Route('/plats/{categorie_id}', name: 'catalogue_plats_by_categorie')]
    public function platsByCategorie(int $categorie_id, EntityManagerInterface $entityManager): Response
    {
        $categorie = $entityManager->getRepository(Categorie::class)->find($categorie_id);
        if (!$categorie) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }
        $plats = $entityManager->getRepository(Plat::class)->findBy(['categorie' => $categorie]);
        return $this->render('catalogue/plats_by_categorie.html.twig', [
            'categorie' => $categorie,
            'plats' => $plats,
        ]);
    }

    #[Route('/categories', name: 'catalogue_categories')]
    public function categories(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        return $this->render('catalogue/categories.html.twig', [
            'categories' => $categories,
        ]);
    }
}
