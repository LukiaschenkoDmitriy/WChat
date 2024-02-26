<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\LoginType;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractLoginController {
    #[Route('/login', name: 'security_login')]
    public function index(Request $request, Security $security): Response
    {
        $formResult = $this->getFormResult($request, LoginType::class, $security);

        if ($formResult->getResponse()) {
            return $formResult->getResponse();
        }

        return $this->render("security/login.html.twig", [
            "form" => $formResult->getForm()->createView()
        ]); 
    }

    public function isValidResponse(?User $user): bool
    {
        if (is_null($user)) return false;
        $userExist = $this->userRepository->findOneBy(["email" => $user->getEmail()]);
        return $userExist && $this->userPasswordHasherInterface->isPasswordValid($userExist, $user->getPassword());
    }

    public function isValidAdditionalObject(mixed $object): bool
    {
        return ($object instanceof FormInterface);
    }

    public function getWrongResponse(?User $user, string $message, mixed $additionalObject): Response
    {
        return ($this->render("security/login.html.twig", [
            "form" => $additionalObject->createView(),
            "error" => $message
        ])->setStatusCode(Response::HTTP_UNAUTHORIZED));
    }

    public function getCorrectResponse(?User $user, string $message, mixed $additionalObject): Response
    {
        return ($this->redirectToRoute("app_about")
            ->setStatusCode(Response::HTTP_OK));
    }
}