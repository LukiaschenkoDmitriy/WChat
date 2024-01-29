<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    private UserPasswordHasherInterface $userPasswordHasherInterface;
    private EntityManagerInterface $entityManagerInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $entityManagerInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route("/app", name:"app")]
    public function app(Request $request)
    {
        return $this->render("App");
    }

    #[Route("/chat", name:"chat")]
    public function chat(Request $request)
    {
        return $this->render("Chat");
    }



    #[Route('/login', name: 'app_login')]
    public function login(Request $request, Security $security)
    {
        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app');
        }

        $logginUser = new User();
        $form = $this->createForm(LoginType::class, $logginUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existUser = $this->entityManagerInterface->getRepository(User::class)->findOneBy([
                "email" => $logginUser->getEmail(),
            ]);

            if ($existUser == null) {
                return $this->render("security/login.html.twig", [
                    "form" => $form->createView(),
                    "error" => "User or password uncorrect"
                ]);
            } else {
                if ($this->userPasswordHasherInterface->isPasswordValid($existUser, $logginUser->getPassword()))
                {
                    $security->login($existUser);
                    return $this->redirectToRoute("chat");
                } else {
                    return $this->render("security/login.html.twig", [
                        "form" => $form->createView(),
                        "error" => "User or password uncorrect"
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
