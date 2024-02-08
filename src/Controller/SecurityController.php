<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    private const ERROR_MESSAGE = "User or password uncorrect";
    private const EMAIL_ALREADY_REGISTERED = "The account under this email is already registered";
    private const PHONE_ALREADY_REGISTERED = "An account has already been registered under this phone number";

    private UserPasswordHasherInterface $userPasswordHasherInterface;
    private EntityManagerInterface $entityManagerInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $entityManagerInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route("/login/forget-password", "security_login_forget")]
    public function forgetPassword(Request $request) {
        // Realize this mailer
        return $this->json("Realise this file src\Controller\LoginController::forgetPassword");
    }

    #[Route('/login', name: 'security_login')]
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
                    "error" => SecurityController::ERROR_MESSAGE
                ]);
            } else {
                if ($this->userPasswordHasherInterface->isPasswordValid($existUser, $logginUser->getPassword()))
                {
                    $security->login($existUser);
                    return $this->redirectToRoute("chat");
                } else {
                    return $this->render("security/login.html.twig", [
                        "form" => $form->createView(),
                        "error" => SecurityController::ERROR_MESSAGE
                    ]);
                }
            }

            return $this->redirectToRoute("app");
        }

        return $this->render("security/login.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route('/register', name: 'security_register')]
    public function register(Request $request, Security $security)
    {
        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('chat');
        }

        $registeredUser = new User();
        $form = $this->createForm(RegisterType::class, $registeredUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->entityManagerInterface->getRepository(User::class);
            if ($repository->findOneBy(["email" => $registeredUser->getEmail()])) {
                return $this->render("security/register.html.twig", [
                    "form" => $form->createView(),
                    "error" => SecurityController::EMAIL_ALREADY_REGISTERED
                ]);
            }

            if ($repository->findOneBy(["phone" => $registeredUser->getPhone(), "countryNumber" => $registeredUser->getCountryNumber()])) {
                return $this->render("security/register.html.twig", [
                    "form" => $form->createView(),
                    "error" => SecurityController::PHONE_ALREADY_REGISTERED
                ]);
            }

            $registeredUser->setAvatar("");
            $registeredUser->setPassword($this->userPasswordHasherInterface->hashPassword($registeredUser, $registeredUser->getPassword()));

            $this->entityManagerInterface->persist($registeredUser);
            $this->entityManagerInterface->flush();
            
            $security->login($registeredUser);

            return $this->redirectToRoute("chat");
        }

        return $this->render("security/register.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route("/logout", name:"security_logout")]
    public function logout(Request $request)
    {
        return $this->json("Logout");
    }
}
