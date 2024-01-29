<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    private const ERROR_MESSAGE = "User or password uncorrect";

    private UserPasswordHasherInterface $userPasswordHasherInterface;
    private EntityManagerInterface $entityManagerInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $entityManagerInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route("/login/forget-password", "app_login_forget")]
    public function forgetPassword(Request $request) {
        // Realize this mailer
        return $this->json("Realise this file src\Controller\LoginController::forgetPassword");
    }

    #[Route('/login', name: 'app_login')]
    public function login(Request $request, Security $security)
    {
        // Test on autenticated user
        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('chat');
        }

        // Create form
        $logginUser = new User();
        $form = $this->createForm(LoginType::class, $logginUser);
        $form->handleRequest($request);

        // Test that button is submitted and all data is valid 
        if ($form->isSubmitted() && $form->isValid()) {
            // Test that user under this email already exist
            $existUser = $this->entityManagerInterface->getRepository(User::class)->findOneBy([
                "email" => $logginUser->getEmail(),
            ]);

            if ($existUser == null) {
                return $this->render("security/login.html.twig", [
                    "form" => $form->createView(),
                    "error" => LoginController::ERROR_MESSAGE
                ]);
            } else {
                if ($this->userPasswordHasherInterface->isPasswordValid($existUser, $logginUser->getPassword()))
                {
                    $security->login($existUser);
                    return $this->redirectToRoute("chat");
                } else {
                    return $this->render("security/login.html.twig", [
                        "form" => $form->createView(),
                        "error" => LoginController::ERROR_MESSAGE
                    ]);
                }
            }

            return $this->redirectToRoute("app");
        }

        return $this->render("security/login.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
