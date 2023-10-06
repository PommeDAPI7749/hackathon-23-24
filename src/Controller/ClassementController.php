<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

    #[Route('/classement/reset', name: 'app.classement.reset')]
    public function reset(UserRepository $userRepository, EntityManagerInterface $manager, TokenStorageInterface $tokenStorage): Response
    {
        if ($tokenStorage->getToken()->getUser()->getEmail() == "mano.raichon@orange.fr") {
            $users = $userRepository->createQueryBuilder('u')
                                ->getQuery()
                                ->getResult();
            foreach ($users as $user) {
                $user->setScore(0);
                $manager->persist($user);
            }

            $manager->flush();
            $this->addFlash(
                'success',
                'Classement réinitialisé'
            );
        } else {
            $this->addFlash(
                'error',
                'Vous ne disposez pas des droits nécessaires.'
            );
        }
        
        return $this->redirectToRoute('home.index');
    }
}
