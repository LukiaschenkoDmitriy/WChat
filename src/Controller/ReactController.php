<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for rendering React application.
 */
class ReactController extends AbstractController
{
    /**
     * Renders the React application.
     * 
     * @Route(
     *     "/app/{reactRouting}",
     *     name="app_react",
     *     requirements={"reactRouting"="^(?!api).+"},
     *     defaults={"reactRouting"=null}
     * )
     */
    public function index()
    {
        return $this->render("/react/index.html.twig");
    }
}
