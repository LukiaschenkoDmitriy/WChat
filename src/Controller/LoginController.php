<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (key_exists("email", $_POST) && key_exists("password", $_POST)) {
            $usersRepository = $entityManager->getRepository(User::class);
            $account = $usersRepository->findOneBy([
                "email" => $_POST["email"],
                "password" => $_POST["password"]
            ]);

            if ($account == null) {
                $checkOnEmail = $usersRepository->findOneBy([
                    "email" => $_POST["email"]
                ]);

                if ($checkOnEmail == null) {
                    return $this->render("login/index.html.twig", [
                        "dataExist" => true
                    ]);
                }
            }
            else {
                return $this->render("chat/index.html.twig", [
                    "user" => $account
                ]);
            }
        }

        return $this->render('login/index.html.twig', [
            "dataExist" => false
        ]);
    }
}
