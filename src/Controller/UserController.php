<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{
    /**
     * This controller alow us to edit User profile
     *
     * @param User $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/utilisateur/edition/{id}', name: 'user.edit', methods: ['GET','POST'])]
    #[Security("is_granted('ROLE_USER') and user === choosenUser")]
    public function edit(User $choosenUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher, TokenStorageInterface $tokenStorage): Response
    {
        if ($tokenStorage->getToken()->getUser() == $choosenUser) {
            $form = $this->createForm(UserType::class, $choosenUser);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                if ($hasher->isPasswordValid($choosenUser, $form->getData()->getPlainPassword()))
                {
                    $user = $form->getData();
                    $manager->persist($user);
                    $manager->flush();
                    $this->addFlash('success', 'Utilisateur modifié !');
                    return $this->redirectToRoute('home.index');
                }
                else
                {
                    $this->addFlash('warning', 'Mot de passe incorect');
                }
            }
        } else {
            $this->addFlash('error', 'Vous ne disposez pas des autorisations');
            return $this->redirectToRoute('home.index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateur.edition-mot-de-passe/{id}', name: 'user.edit.password', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user === choosenUser")]
    public function editPassword(User $choosenUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher, TokenStorageInterface $tokenStorage):Response
    {
        if ($tokenStorage->getToken()->getUser() == $choosenUser) {
            $form = $this->createForm(UserPasswordType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($hasher->isPasswordValid($choosenUser, $form->getData()['plainPassword']))
                {
                    $choosenUser->setCreatedAt(new \DateTimeImmutable());
                    $choosenUser->setPlainPassword(
                        $form->getData()['newPassword']
                    );
                    $manager->persist($choosenUser);
                    $manager->flush();

                    $this->addFlash('success', 'Mot de passe modifié !');
                    return $this->redirectToRoute('home.index');
                }else{
                    $this->addFlash('warning', 'Mote de passe incorrect ');
                }
            }
        } else {
            $this->addFlash('error', 'Vous ne disposez pas des autorisations');
            return $this->redirectToRoute('home.index');
        }
        return $this->render('user/edit_password.html.twig', ['form' => $form]);
    }
}
