<?php

namespace App\Service;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\InputBag;

class RegisterFormValidator extends FormValidator
{
    public function __construct(
        EntityManagerInterface $entityManagerInterface) 
    {
        parent::__construct($entityManagerInterface);
    }

    public function validate(InputBag $data) : array | null
    {
        $emailErrors = $this->validateEmail($data->get('email'));
        if ($emailErrors) return $emailErrors;

        $passwordErrors = $this->validatePassword($data->get("password"), $data->get("rep_password"));
        if ($passwordErrors) return $passwordErrors;

        return [];
    }

    public function validateEmail(String $email) : array | null
    {
        $userExist = $this->em->getRepository(User::class)->findOneBy(["email"=> $email]);
        if ($userExist) {
            return ["error" => "The account under this email already registered"];
        } return null;
    }

    public function validatePassword(String $password, String $rep_password) : array | null
    {
        if ($password != $rep_password) return ["error" => "Passwords are not the same "];
        return null;
    }
}