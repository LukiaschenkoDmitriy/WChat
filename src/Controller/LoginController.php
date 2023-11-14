<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(EntityManagerInterface $entityManager ): Response
    {
        if (key_exists("email", $_POST) && key_exists("password", $_POST)) {
            $usersRepository = $entityManager->getRepository(User::class);
            $accountExist = $usersRepository->findOneBy([
                "email" => $_POST["email"]
            ]);

            if ($accountExist == null) {
                return $this->render("login/index.html.twig", [
                    "accountNotExistError" => true
                ]);
            }

            $account = $usersRepository->findOneBy([
                "email" => $_POST["email"],
                "password" => $_POST["password"]
            ]);

            if ($account == null) {
                return $this->render("login/index.html.twig", [
                    "incorrectPasswordError" => true
                ]);
            }
            
            else {
                return $this->render("chat/index.html.twig", [
                    "user" => $account
                ]);
            }
        }

        return $this->render('login/index.html.twig');
    }
}
