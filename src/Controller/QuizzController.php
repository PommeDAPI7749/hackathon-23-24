<?php

namespace App\Controller;

use App\Entity\Quizz;
use App\Repository\QuizzRepository;
use App\Repository\UserRepository;
use App\Service\QuizzService;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizzController extends AbstractController
{
    #[Route('/quizz', name: 'app.quizz')]
    public function index(UserRepository $userRepo, EntityManagerInterface $manager): Response
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
        $user = $this->getUser();
        $user = $userRepo->findOneBy(['id' => $user->getId()]);
        $quizz->setUser($user);
        $user->addQuizz($quizz);
        $manager->persist($quizz);
        $manager->persist($user);
        $manager->flush();

        return $this->render('quizz/index.html.twig', [
            'quizz' => $quizz
        ]);
    }
    #[Route('/quizz/show/{id}', name: 'app.quizz.show')]
   public function show($id, QuizzRepository $quizzRepository){
        $quizz = $quizzRepository->findOneBy(['id'=>$id]);
        dd($quizz);
        return $this->render('quizz/show.html.twig', [
            'quizz' => $quizz

        ]);
    }
}
