<?php

namespace App\Controller;

use App\Entity\Quizz;
use App\Repository\QuizzRepository;
use App\Repository\UserRepository;
use App\Services\QuizzService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    
    #[Route('/quizz/resume/{id}', name: 'app.quizz.finish')]
    public function resume($id, Request $request, EntityManagerInterface $manager, QuizzRepository $quizzRepository) {
        $quizz = $quizzRepository->findOneBy(['id'=>$id]);

        return $this->render('quizz/index.html.twig', [
            'quizz' => $quizz
        ]);
    }

    #[Route('/quizz/{id}', name: 'app.quizz.submit', methods: ['POST'])]
    public function submit($id, Request $request, EntityManagerInterface $manager, QuizzRepository $quizzRepository) {
        $quizz = $quizzRepository->findOneBy(['id'=>$id]);
        $answers = [0,0];
        $userAnswers = [];
        $user = $quizz->getUser();
        $score = $user->getScore();
        foreach ($quizz->getData() as $index => $question) {
            $userAnswers[$index] = $request->request->get($index);
            if ($question->correct_answer == $request->request->get($index)) {
                $answers[0]++;
                $score += 1;
            } else {
                $answers[1]++;
            }
        }
        $quizz->setIsFinished(true);
        $quizz->setGoodAnswer($answers[0]);
        $quizz->setWrongAnswer($answers[1]);
        $quizz->setUserAnswers($userAnswers);
        $user->setScore($score);
        $manager->persist($quizz);
        $manager->persist($user);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre reponsee au quizz a bien ete enregistree, voici un recapittulatif de vos 5 derniers resultats.'
        );
        return $this->redirectToRoute('app.recap');
    }


    #[Route('/quizz/show/{id}', name: 'app.quizz.show')]
    public function show($id, QuizzRepository $quizzRepository){
        $quizz = $quizzRepository->findOneBy(['id'=>$id]);
        return $this->render('quizz/show.html.twig', [
            'quizz' => $quizz

        ]);
    }
}
