<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassementController extends AbstractController
{
    #[Route('/classement', name: 'app.classement')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->createQueryBuilder('u')
                                ->orderBy('CASE WHEN u.score IS NULL THEN 1 ELSE 0 END, u.score', 'DESC')
                                ->getQuery()
                                ->getResult();
        return $this->render('classement/index.html.twig', [
            'users' => $users
        ]);
    }
}
