<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegisterController extends AbstractController
{
    /**
     * Fonction index push en BDD les informations de l'inscription 
     */
    #[Route(path: '/register', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
    {
        // j'instencie mon objet User
        $user = new User();
        // je crée mon formulaire par rapport a RegisterType
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        // si $form et submit && $form est valid alors
        if($form->isSubmitted() && $form->isValid()){

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            // je prepare ma requete
            $em->persist($user);
            // je push en BDD
            $em->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
