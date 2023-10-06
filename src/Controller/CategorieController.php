<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    private $categories = [
        ['id' => 0, 'name' => 'Musique'],
        ['id' => 1, 'name' => 'Rock'],
        ['id' => 2, 'name' => 'Pop'],
        ['id' => 3, 'name' => 'Hip-hop'],
        ['id' => 4, 'name' => 'Jazz'],
        ['id' => 5, 'name' => 'Country'],
        ['id' => 6, 'name' => 'R&B'],
        ['id' => 7, 'name' => 'Reggae'],
    ];    

    #[Route('/categories/index', name: 'app.categories.index')]
    public function index(CategorieRepository $categorieRepository, EntityManagerInterface $manager){
        $categories = $categorieRepository->findAll();
        if ($categories == []) {
            foreach ($this->categories as $setCategory) {
                $category = new Categorie;
                $category->setName($setCategory['name']);
                $manager->persist($category);
            }

            $manager->flush();
            $categories = $categorieRepository->findAll();
        }

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories
        ]);
    }
}
