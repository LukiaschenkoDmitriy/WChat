<?php

namespace App\Security;

use App\Data\FormResultData;
use App\Entity\User;
use App\Interface\Security\AdditionalObjectProviderInterface;
use App\Interface\Security\FormSecurityInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract class providing common functionality for form-based security controllers.
 */
abstract class AbstractFormSecurityController extends AbstractSecurityController implements FormSecurityInterface, AdditionalObjectProviderInterface {

    /**
     * Additional object associated with the form.
     */
    private mixed $additionalObject = null;

    /**
     * Gets the result of form submission.
     *
     * @param Request $request The HTTP request object.
     * @param string $typeClass The class name of the form.
     * @param Security $security The Symfony security component.
     * @return FormResultData The result data of form submission.
     */
    public function getFormResult(Request $request, string $typeClass, Security $security): FormResultData
    {
        // Create a new user instance
        $user = new User();

        // Create form instance
        $form = $this->createForm($typeClass, $user);

        // Set additional object for the form
        $this->setAdditionalObject($form);

        // Handle form submission
        $form->handleRequest($request);

        // If form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get response based on user authentication
            $response = $this->getResponse($user, $security);

            // If authentication fails, return form result with response
            if ($response->getStatusCode() != Response::HTTP_OK) {
                return FormResultData::createInstanceOfObject($form, $response);
            } 

            // Authenticate user and return form result with response
            $token = $this->jWTService->getToken($user->getEmail(), "+1400 minutes");
            $this->setAdditionalObject($token);

            $security->login($this->getFullUser($user));
            return FormResultData::createInstanceOfObject($form, $response);
        }

        // Return form result with null response
        return FormResultData::createInstanceOfObject($form, null);
    }

    /**
     * Sets the additional object associated with the form.
     *
     * @param mixed $addObject The additional object.
     * @return void
     */
    public function setAdditionalObject(mixed $addObject)
    {
        // Check if the additional object is valid
        if (!$this->isValidAdditionalObject($addObject)) return;

        // Set the additional object
        $this->additionalObject = $addObject;
    }

    /**
     * Gets the additional object associated with the form.
     *
     * @return mixed The additional object.
     */
    public function getAdditionalObject(): mixed
    {
        return $this->additionalObject;
    }
}
