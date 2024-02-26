<?php

namespace Api\Controller\Security;

use Api\Interface\ApiRequestValidator;
use App\Controller\Security\AbstractSecurityController;
use App\Entity\User;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiSecurityController extends AbstractSecurityController implements ApiRequestValidator{

    public function getResponse(?User $user, Security $security): Response
    {
        if ($this->isUserAutorizated($security)) return $this->getWrongResponse($user, $this->getAuhorizatedMessage(), null);
        if (!$this->isValidResponse($user)) return $this->getWrongResponse($user, $this->getWrongResponseMessage(), null);

        return $this->getCorrectResponse($user, $this->getCorrectResponseMessage(), null);
    }

    public function getWrongResponseMessage(): string
    {
        return "Access denied. Invalid login or password.";
    }

    public function getCorrectResponseMessage(): string
    {
        return "You have successfully logged in.";
    }

    public function getFullUser(?User $user): ?User
    {
        if (is_null($user)) return null;
        $this->userRepository->findOneBy(["email" => $user->getEmail()]);
    }
}