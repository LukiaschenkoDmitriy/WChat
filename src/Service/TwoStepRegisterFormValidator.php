<?php

namespace App\Service;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\InputBag;

class TwoStepRegisterFormValidator extends FormValidator
{
    public function __construct(
        EntityManagerInterface $entityManagerInterface) 
    {
        parent::__construct($entityManagerInterface);
    }

    public function validate(InputBag $data) : array | null
    {
        $usernameError = $this->validateUsername($data->get('username'));
        if ($usernameError) return $usernameError;

        return null;
    }

    public function validateUsername(String $username) : array | null
    {
        $userExist = $this->em->getRepository(User::class)->findOneBy(["username"=> $username]);
        if ($userExist) {
            return ["error" => "The account under this username already exist"];
        } return null;
    }
}