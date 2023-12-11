<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\RegistrationStepTwoType;
use App\Service\RegisterFormValidator;
use App\Service\TwoStepRegisterFormValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $uphi;
    private Security $security;
    public function __construct(
        UserPasswordHasherInterface $uphi,
        EntityManagerInterface $em,
        Security $security
    ) {
        $this->em = $em;
        $this->uphi = $uphi;
        $this->security = $security;
    }

    #[Route("/register", name:"app_register_post", methods:"POST")]
    public function registerProcess(Request $request, RegisterFormValidator $validator): Response
    {
        $user = new User();
        $formError = $validator->validate($request->request);
        if ($formError) return $this->render("registration/register.html.twig", $formError);
        $user->setEmail($request->request->get("email"));
        $user->setPassword(
            $this->uphi->hashPassword(
                $user,
                $request->request->get("password")
            )
        );

        $this->em->persist($user);
        $this->em->flush();

        $this->security->login($user);

        return $this->redirectToRoute('app_chat');
    }

    #[Route('/register', name: 'app_register_get', methods:"GET")]
    public function register(): Response
    {
        return $this->render('registration/register.html.twig');
    }
}
