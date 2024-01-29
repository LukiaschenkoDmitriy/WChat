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

class RegisterController extends AbstractController
{
    private const NOT_CORRECT_EMAIL_ERROR = "The format of this email is not correct";
    private const NOT_HARD_PASSWORD = "Your password is not secure, include symbols, numbers, letters.";
    private const EMAIL_ALREADY_REGISTERED = "The account under this email is already registered";
    private const PHONE_ALREADY_REGISTERED = "An account has already been registered under this phone number";

    private UserPasswordHasherInterface $userPasswordHasherInterface;
    private EntityManagerInterface $entityManagerInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $entityManagerInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/register', name: 'app_register')]
    public function login(Request $request, Security $security)
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
                    "error" => RegisterController::EMAIL_ALREADY_REGISTERED
                ]);
            }

            if ($repository->findOneBy(["phone" => $registeredUser->getPhone()])) {
                return $this->render("security/register.html.twig", [
                    "form" => $form->createView(),
                    "error" => RegisterController::PHONE_ALREADY_REGISTERED
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
}
