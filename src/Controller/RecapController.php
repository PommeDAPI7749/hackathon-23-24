<?php

namespace App\Controller;

use App\Repository\QuizzRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class RecapController extends AbstractController
{
    #[Route('/recap', name: 'app.recap')]
    #[IsGranted('ROLE_USER')]
    public function index(QuizzRepository $quizzRepository): Response
    {
        $user = $this->getUser();
        $quizzs = $quizzRepository->findBy(
            ['user' => $user],
            ['date' => 'DESC']
        );
    
        // Triez les parties par date en ordre décroissant
        usort($quizzs, function ($a, $b) {
            return $b->getDate() <=> $a->getDate();
        });
        
        $last5Quizzs = array_slice($quizzs, 0, 5);
        
        return $this->render('recap/index.html.twig', [
            'quizzs' => $last5Quizzs,
        ]);
    }
}
