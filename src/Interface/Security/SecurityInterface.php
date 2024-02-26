<?php

namespace App\Interface\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface SecurityInterface 
extends ResponseProviderInterface, UserAutorizatedProviderInterface, AdditionalObjectProviderInterface
{
    public function index(Request $request, Security $security): Response;
}