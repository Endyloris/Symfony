<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Categorie;
use App\Entity\Plat;
use App\Entity\Utilisateur;
use App\Entity\Commande;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTime;

class BDD extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Catégories
        $categorie1 = new Categorie();
        $categorie1->setLibelle('Entrées');
        $categorie1->setImage('image/Categorie/Entrées.jpeg');
        $categorie1->setActive(true);
        $manager->persist($categorie1);

        $categorie2 = new Categorie();
        $categorie2->setLibelle('Plats principaux');
        $categorie2->setImage('image/Categorie/Plat.jpeg');
        $categorie2->setActive(true);
        $manager->persist($categorie2);

        $categorie3 = new Categorie();
        $categorie3->setLibelle('Desserts');
        $categorie3->setImage('image/Categorie/Dessert.jpeg');
        $categorie3->setActive(true);
        $manager->persist($categorie3);

        $manager->flush();

        // Rafraîchir les catégories pour obtenir leurs IDs
        $manager->refresh($categorie1);
        $manager->refresh($categorie2);
        $manager->refresh($categorie3);

        // Obtenir les IDs des catégories
        $categorie1Id = $categorie1->getId();
        $categorie2Id = $categorie2->getId();
        $categorie3Id = $categorie3->getId();

        // Entrées
        $entrees = [
            [
                'libelle' => 'Salade au chèvre chaud',
                'description' => 'Salade de mesclun avec des toasts de fromage de chèvre chaud, noix et vinaigrette au miel.',
                'prix' => 12.00,
                'image' => 'image/Entrées/SaladeChevre.jpeg',
                'active' => true,
                'categorie_id' => $categorie1Id,
            ],
            [
                'libelle' => 'Soupe à l\'Oignon',
                'description' => 'Soupe traditionnelle française garnie de croûtons et gratinée au fromage.',
                'prix' => 10.00,
                'image' => 'image/Entrées/SoupeOignon.jpeg',
                'active' => true,
                'categorie_id' => $categorie1Id,
            ],
            [
                'libelle' => 'Foie Gras',
                'description' => 'Foie gras de canard servi avec du pain brioché et une confiture de figues.',
                'prix' => 18.00,
                'image' => 'image/Entrées/FoieCanard.jpeg',
                'active' => true,
                'categorie_id' => $categorie1Id,
            ],
            [
                'libelle' => 'Ceviche au saumon',
                'description' => 'Dés de saumon frais marinés dans du jus de citron vert, coriandre, oignons rouges et piment, servis avec des chips de maïs.',
                'prix' => 13.00,
                'image' => 'image/Entrées/CevicheSaumon.jpeg',
                'active' => true,
                'categorie_id' => $categorie1Id,
            ],
            [
                'libelle' => 'Carpaccio au boeuf',
                'description' => 'Fines tranches de bœuf cru marinées à l’huile d’olive et au citron, servies avec des copeaux de parmesan et des roquettes.',
                'prix' => 14.00,
                'image' => 'image/Entrées/CarpaccioBoeuf.jpeg',
                'active' => true,
                'categorie_id' => $categorie1Id,
            ],
        ];

        foreach ($entrees as $entreeData) {
            $entree = new Plat();
            $entree->setLibelle($entreeData['libelle'])
                   ->setDescription($entreeData['description'])
                   ->setPrix($entreeData['prix'])
                   ->setImage($entreeData['image'])
                   ->setActive($entreeData['active']);
                   
            $categorie = $manager->getRepository(Categorie::class)->find($entreeData['categorie_id']);
            $entree->setCategorie($categorie);

            $manager->persist($entree);
        }

        // Plats principaux
        $plats = [
            [
                'libelle' => 'Filet de Bœuf Rossini',
                'description' => 'Filet de bœuf tendre garni de foie gras poêlé, servi avec une sauce au vin rouge et des légumes de saison.',
                'prix' => 32.00,
                'image' => 'image/plats/FIletdeboeuf.jpeg',
                'active' => true,
                'categorie_id' => $categorie2Id,
            ],
            [
                'libelle' => 'Saumon en Croûte d’Herbes',
                'description' => 'Filet de saumon frais enrobé d’un mélange d’herbes fraîches, cuit au four et servi avec une purée de pommes de terre à l’ail.',
                'prix' => 25.00,
                'image' => 'image/plats/Saumonherbes.jpeg',
                'active' => true,
                'categorie_id' => $categorie2Id,
            ],
            [
                'libelle' => 'Poulet à la Normande',
                'description' => 'Poulet mijoté dans une sauce crémeuse aux pommes et au cidre, accompagné de riz basmati.',
                'prix' => 21.00,
                'image' => 'image/plats/Pouletnormand.jpeg',
                'active' => true,
                'categorie_id' => $categorie2Id,
            ],
            [
                'libelle' => 'Risotto aux Champignons Sauvages',
                'description' => 'Risotto crémeux aux champignons sauvages, parfumé à l’huile de truffe et parsemé de parmesan râpé.',
                'prix' => 18.00,
                'image' => 'image/plats/RisottoChampignon.jpeg',
                'active' => true,
                'categorie_id' => $categorie2Id,
            ],
            [
                'libelle' => 'Linguine aux Fruits de Mer',
                'description' => 'Pâtes linguine servies avec une sélection de fruits de mer frais, nappées d’une sauce tomate épicée.',
                'prix' => 27.00,
                'image' => 'image/plats/Linguini.jpeg',
                'active' => true,
                'categorie_id' => $categorie2Id,
            ],
        ];

        foreach ($plats as $platData) {
            $plat = new Plat();
            $plat->setLibelle($platData['libelle'])
                 ->setDescription($platData['description'])
                 ->setPrix($platData['prix'])
                 ->setImage($platData['image'])
                 ->setActive($platData['active']);
                 
            $categorie = $manager->getRepository(Categorie::class)->find($platData['categorie_id']);
            $plat->setCategorie($categorie);

            $manager->persist($plat);
        }

        // Desserts
        $desserts = [
            [
                'libelle' => 'Tarte au Citron Meringuée',
                'description' => 'Pâte sablée garnie de crème au citron, surmontée d\'une meringue légère et dorée.',
                'prix' => 7.00,
                'image' => 'image/Desserts/citronmeringue.jpeg',
                'active' => true,
                'categorie_id' => $categorie3Id,
            ],
            [
                'libelle' => 'Fondant au Chocolat',
                'description' => 'Gâteau au chocolat noir fondant, servi avec une boule de glace à la vanille.',
                'prix' => 8.00,
                'image' => 'image/Desserts/Fondantchocolat.jpeg',
                'active' => true,
                'categorie_id' => $categorie3Id,
            ],
            [
                'libelle' => 'Tarte Tatin',
                'description' => 'Tarte renversée aux pommes caramélisées, servie tiède avec une boule de glace à la vanille.',
                'prix' => 8.00,
                'image' => 'image/Desserts/Tartatin.jpeg',
                'active' => true,
                'categorie_id' => $categorie3Id,
            ],
            [
                'libelle' => 'Crème Brûlée',
                'description' => 'Crème à la vanille recouverte d\'une fine couche de caramel croquant.',
                'prix' => 6.00,
                'image' => 'image/Desserts/Cremebrulee.jpeg',
                'active' => true,
                'categorie_id' => $categorie3Id,
            ],
            [
                'libelle' => 'Tiramisu',
                'description' => 'Dessert italien classique avec des couches de mascarpone et de biscuit imbibé de café.',
                'prix' => 7.50,
                'image' => 'image/Desserts/TIramisu.jpeg',
                'active' => true,
                'categorie_id' => $categorie3Id,
            ],
        ];

        foreach ($desserts as $dessertData) {
            $dessert = new Plat();
            $dessert->setLibelle($dessertData['libelle'])
                    ->setDescription($dessertData['description'])
                    ->setPrix($dessertData['prix'])
                    ->setImage($dessertData['image'])
                    ->setActive($dessertData['active']);
                    
            $categorie = $manager->getRepository(Categorie::class)->find($dessertData['categorie_id']);
            $dessert->setCategorie($categorie);

            $manager->persist($dessert);
        }

        // Création des utilisateurs
        $utilisateursData = [
            [
                'email' => 'user1@example.com',
                'password' => 'password123',
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'telephone' => '0601020304',
                'adresse' => '123 Rue de Paris',
                'cp' => '75001',
                'ville' => 'Paris',
                'roles' => ['ROLE_ADMIN']
            ],
            [
                'email' => 'user2@example.com',
                'password' => 'password123',
                'nom' => 'Martin',
                'prenom' => 'Marie',
                'telephone' => '0610203040',
                'adresse' => '456 Rue de Lyon',
                'cp' => '69001',
                'ville' => 'Lyon',
                'roles' => ['ROLE_CLIENT']
            ]
        ];

        $utilisateurIds = [];
        foreach ($utilisateursData as $userData) {
            $utilisateur = new Utilisateur();
            $utilisateur->setEmail($userData['email'])
                        ->setPassword($this->passwordHasher->hashPassword($utilisateur, $userData['password']))
                        ->setNom($userData['nom'])
                        ->setPrenom($userData['prenom'])
                        ->setTelephone($userData['telephone'])
                        ->setAdresse($userData['adresse'])
                        ->setCp($userData['cp'])
                        ->setVille($userData['ville'])
                        ->setRoles($userData['roles']);

            $manager->persist($utilisateur);
            $manager->flush(); // Persist the user to get the ID
            $utilisateurIds[] = $utilisateur->getId(); // Store the ID
        }

        // Création des commandes
        $commandesData = [
            [
                'date_commande' => new DateTime('2023-01-01 10:00:00'),
                'total' => 100.00,
                'etat' => 0, // Enregistré/payé
                'utilisateur_id' => $utilisateurIds[0] // ID du premier utilisateur
            ],
            [
                'date_commande' => new DateTime('2023-01-02 12:00:00'),
                'total' => 50.00,
                'etat' => 3, // Livrée
                'utilisateur_id' => $utilisateurIds[1] // ID du deuxième utilisateur
            ],
            [
                'date_commande' => new DateTime('2023-01-03 14:00:00'),
                'total' => 75.00,
                'etat' => 2, // En cours de livraison
                'utilisateur_id' => $utilisateurIds[0] // ID du premier utilisateur
            ]
        ];

        foreach ($commandesData as $commandeData) {
            $commande = new Commande();
            $commande->setDateCommande($commandeData['date_commande'])
                     ->setTotal($commandeData['total'])
                     ->setEtat($commandeData['etat']);
                     
            $utilisateur = $manager->getRepository(Utilisateur::class)->find($commandeData['utilisateur_id']);
            $commande->setUtilisateur($utilisateur);

            $manager->persist($commande);
        }

        $manager->flush();
    }
}
