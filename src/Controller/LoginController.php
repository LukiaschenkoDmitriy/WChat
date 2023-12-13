<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\LoginFormValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    private EntityManagerInterface $em;
    private Security $security;
    private LoginFormValidator $lfv;
    public function __construct(
        EntityManagerInterface $em,
        Security $security,
        LoginFormValidator $lfv) 
    {
        $this->em = $em;
        $this->security = $security;
        $this->lfv = $lfv;
    }

    #[Route(path: "/login", name:"app_login_post", methods:"POST")]
    public function loginProcess(Request $request) {
        if ($this->security->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_chat");
        }

        $formError = $this->lfv->validate($request->request);
        if ($formError) return $this->render("login/index.html.twig", $formError);

        $user = $this->em->getRepository(User::class)->findOneBy(["email"=> $request->request->get("_username")]);
        $this->security->login($user);

        return $this->redirectToRoute("app_chat");
    }

    #[Route(path:"/login", name:"app_login_get", methods:"GET")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->security->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_chat");
        }

        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            "last_username" => $lastUsername
        ]);
    }
}
