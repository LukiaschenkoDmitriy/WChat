<?php

namespace App\Service;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginFormValidator extends FormValidator
{
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        UserPasswordHasherInterface $userPasswordHasherInterface) 
    {
        parent::__construct($entityManagerInterface);
        $this->passwordHasher = $userPasswordHasherInterface;
    }

    public function validate(InputBag $data) : array | null
    {
        $emailErrors = $this->validateEmail($data->get('_username'));
        if ($emailErrors) return $emailErrors;

        $passwordErrors = $this->validatePassword($data->get("_username"), $data->get("_password"));
        if ($passwordErrors) return $passwordErrors;

        return null;
    }

    public function validateEmail(String $email) : array | null
    {
        $userExist = $this->em->getRepository(User::class)->findOneBy(["email"=> $email]);
        if (!$userExist) {
            return ["error" => "The account under this email is not registered"];
        } return null;
    }

    public function validatePassword(String $email, String $password) : array | null
    {
        $user = $this->em->getRepository(User::class)->findOneBy(["email"=> $email]);
        $passwordTrue = $this->passwordHasher->hashPassword($user, $password);

        if (!$passwordTrue) {
            return ["error" => "Incorrect password"];
        } return null;
    }
}