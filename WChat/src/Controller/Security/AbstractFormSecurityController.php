<?php

namespace App\Controller\Security;

use App\Data\FormResultData;
use App\Entity\User;
use App\Interface\Security\FormSecurityInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractFormSecurityController extends AbstractSecurityController implements FormSecurityInterface {

    public function getFormResult(Request $request, string $typeClass, Security $security): FormResultData
    {
        $user = new User();
        $form = $this->createForm($typeClass, $user);

        $this->setAdditionalObject($form);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->getResponse($user, $security);
            if ($response->getStatusCode() != Response::HTTP_OK) {
                return FormResultData::createInstanceOfObject($form, $response);
            } 

            $security->login($this->getFullUser($user));
            return FormResultData::createInstanceOfObject($form, $response);
        }

        return FormResultData::createInstanceOfObject($form, null);
    }
}