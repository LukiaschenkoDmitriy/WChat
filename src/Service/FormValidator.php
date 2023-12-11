<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\InputBag;

abstract class FormValidator
{
    protected EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $entityManagerInterface) {
        $this->em = $entityManagerInterface;
    }

    public abstract function validate(InputBag $data) : array | null;
}