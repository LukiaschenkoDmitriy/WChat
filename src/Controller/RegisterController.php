<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (key_exists("email", $_POST)) {
            $usersRepository = $entityManager->getRepository(User::class);
            $existAccount = $usersRepository->findOneBy([
                "email" => $_POST["email"]
            ]);

            if ($existAccount != null) {
                return $this->render("register/index.html.twig", [
                    "accountExistError" => true
                ]);
            }

            if ($_POST["password"] != $_POST["rep_password"]) {
                return $this->render("register/index.html.twig", [
                    "passwordRepeatError" => true
                ]);
            }

            $account = new User();
            $account->setEmail($_POST["email"]);
            $account->setPassword($_POST["password"]);
            $account->setUsername($_POST["name"]);

            $entityManager->persist($account);
            $entityManager->flush();

            return $this->render("chat/index.html.twig", [
                "user" => $account
            ]);
        }

        return $this->render('register/index.html.twig');
    }
}
