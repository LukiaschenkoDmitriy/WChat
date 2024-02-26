<?php

namespace App\Interface\Security;

use App\Data\FormResultData;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface FormSecurityInterface
 * 
 * This interface defines a method for handling form security.
 */
interface FormSecurityInterface {
    /**
     * Get the form result data based on the request, form type class, and security.
     * 
     * @param Request $request The HTTP request object.
     * @param string $typeClass The fully qualified class name of the form type.
     * @param Security $security The Symfony security component.
     * @return FormResultData The form result data.
     */
    public function getFormResult(Request $request, string $typeClass, Security $security): FormResultData;
}
