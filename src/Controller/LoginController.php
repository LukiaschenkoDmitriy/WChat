<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    #[Route('/login', name: 'app_login')]
    public function login(Request $request,
                          EntityManagerInterface $entityManager,
                          Security $security,
                          UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if ($security->getUser() != null) {
            return $this->redirectToRoute("app_chat");
        }

        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $entityManager->getRepository(User::class)->findOneBy([
                'email'=> $form->get('email')->getData(),
            ]);

            if ($user == null) {
                return $this->render("login/index.html.twig", [
                    "authError" => "Not found user",
                    "loginForm" => $form->createView()
                ]);
            }

            $isPasswordValid = $userPasswordHasher->isPasswordValid($user, $form->get("password")->getData());

            if (!$isPasswordValid) {
                return $this->render("login/index.html.twig", [
                    "authError" => "Password is wrong",
                    "loginForm" => $form->createView()
                ]);
            }

            $security->login($user);

            return $this->redirectToRoute('app_chat');
        }

        return $this->render('login/index.html.twig', [
            "loginForm" => $form->createView()
        ]);
    }
}
