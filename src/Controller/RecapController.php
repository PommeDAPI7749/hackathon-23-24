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
    public function index(UserRepository $userRepository, QuizzRepository $quizzRepository): Response
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
        // $quizData = [
        //     'score' => rand(0, 5),
        //     'questions' => [
        //         ['text' => 'Question 1', 'correct' => (bool) rand(0, 1)],
        //         ['text' => 'Question 2', 'correct' => (bool) rand(0, 1)],
        //         ['text' => 'Question 3', 'correct' => (bool) rand(0, 1)],
        //         ['text' => 'Question 4', 'correct' => (bool) rand(0, 1)],
        //         ['text' => 'Question 5', 'correct' => (bool) rand(0, 1)],
        //     ],
        //     'correctCount' => rand(0, 5),
        //     'incorrectCount' => rand(0, 5),
        // ];
        // for ($i=0; $i < 10; $i++) { 
        //     $previousGames[$i] = [
        //     'score' => $quizData['score'],
        //     'correctCount' => $quizData['correctCount'],
        //     'incorrectCount' => $quizData['incorrectCount'],
        // ];
        // }
        
        // $previousGames = array_slice($previousGames, -5);
        return $this->render('recap/index.html.twig', [
            'controller_name' => 'RecapController',
            'quizzs' => $last5Quizzs,
            // 'previousGames' => $previousGames,
        ]);
    }
}
