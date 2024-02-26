<?php

namespace App\Interface\Security;

use App\Data\FormResultData;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

interface FormSecurityInterface {
    public function getFormResult(Request $request, string $typeClass, Security $security): FormResultData;
}