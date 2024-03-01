<?php

namespace App\Interface\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface SecurityInterface
 * 
 * This interface extends ResponseProviderInterface and UserAutorizatedProviderInterface
 * and defines a method for handling security operations.
 */
interface SecurityInterface extends ResponseProviderInterface, UserAutorizatedProviderInterface {
    /**
     * Handle security operations and return a response.
     * 
     * @param Request $request The HTTP request object.
     * @param Security $security The Symfony security component.
     * @return Response The HTTP response object.
     */
    public function index(Request $request, Security $security): Response;
}
