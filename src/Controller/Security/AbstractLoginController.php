<?php

namespace App\Controller\Security;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractLoginController extends AbstractFormSecurityController 
{
    public function getFullUser(?User $user): ?User
    {
        return $this->userRepository->findOneBy(["email" => $user->getEmail()]);
    }

    public function getResponse(?User $user, Security $security): Response
    {
        if ($this->isUserAutorizated($security)) return $this->getWrongResponse($user, $this->getAuhorizatedMessage(), $this->getAdditionalObject());
        if (!$this->isValidResponse($user)) return $this->getWrongResponse($user, $this->getWrongResponseMessage(), $this->getAdditionalObject());

        return $this->getCorrectResponse($user, $this->getCorrectResponseMessage(), $this->getAdditionalObject());
    }

    public function getWrongResponseMessage(): string
    {
        return "User or password uncorrect";
    }

    public function getCorrectResponseMessage(): string
    {
        return "Autorization is correct";
    }
}