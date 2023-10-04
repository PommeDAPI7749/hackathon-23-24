<?php

namespace App\Controller;

use App\Entity\Quizz;
use App\Service\QuizzService;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizzController extends AbstractController
{
    #[Route('/quizz', name: 'app.quizz')]
    public function index(EntityManagerInterface $manager): Response
    {
        $quizz = new Quizz;
        $quizz->setDate(new DateTime());
        $quizzService = new QuizzService;
        // $data = $quizzService->generateQuizzData();
        $data = json_decode("["
        ."\n    {"
        ."\n        \"question\": \"Quel temps fait il aujourd'hui ?\","
        ."\n        \"answers\": ["
        ."\n            \"Pluvieux\","
        ."\n            \"Ensoleille\","
        ."\n            \"Nuageux\","
        ."\n            \"Orageux\""
        ."\n        ],"
        ."\n        \"correct_answer\": 1"
        ."\n    },"
        ."\n    {"
        ."\n        \"question\": \"Quel temps fait il aujourd'hui ?\","
        ."\n        \"answers\": ["
        ."\n            \"Pluvieux\","
        ."\n            \"Ensoleille\","
        ."\n            \"Nuageux\","
        ."\n            \"Orageux\""
        ."\n        ],"
        ."\n        \"correct_answer\": 1"
        ."\n    },"
        ."\n    {"
        ."\n        \"question\": \"Quel temps fait il aujourd'hui ?\","
        ."\n        \"answers\": ["
        ."\n            \"Pluvieux\","
        ."\n            \"Ensoleille\","
        ."\n            \"Nuageux\","
        ."\n            \"Orageux\""
        ."\n        ],"
        ."\n        \"correct_answer\": 1"
        ."\n    }"
        ."\n]");
        $quizz->setData($data);
        $manager->persist($quizz);
        $manager->flush();

        return $this->render('quizz/index.html.twig', [
            'controller_name' => 'QuizzController',
            'quizz' => $quizz
        ]);
    }
}
