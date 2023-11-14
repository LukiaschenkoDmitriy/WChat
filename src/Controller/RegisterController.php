<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
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
            
            $passswordError = $this->getPasswordError($_POST["password"]);
            if ($passswordError != null) {
                return $passswordError;
            }

            $account = new User();

            $account->setUsername($_POST['name']);
            $account->setEmail($_POST["email"]);
            $account->setPhone($_POST["phone_number"]);
            $hashedPassword = $passwordHasher->hashPassword($account, $_POST["password"]);
            $account->setPassword($hashedPassword);

            $entityManager->persist($account);
            $entityManager->flush();

            return $this->render("chat/index.html.twig", [
                "user" => $account
            ]);
        }

        return $this->render('register/index.html.twig');
    }

    private function getPasswordError($password) {
        if (strlen($password) < 8) {
            return $this->render("register/index.html.twig", [
                "lenthPasswordError" => true
            ]);
        }

        if (!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password)) {
            return $this->render("register/index.html.twig", [
                "oneBigWordError" => true
            ]);
        }
    
        if (!preg_match("/[0-9]/", $password)) {
            return $this->render("register/index.html.twig", [
                "oneNumberError" => true
            ]);
        }
    
        return null;
    }
}
