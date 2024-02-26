<?php

namespace App\Interface\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface SecurityInterface 
extends ResponseProviderInterface, UserAutorizatedProviderInterface
{
    public function index(Request $request, Security $security): Response;
}