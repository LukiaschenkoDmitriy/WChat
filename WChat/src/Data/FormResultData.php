<?php

namespace App\Data;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class FormResultData {
    private ?FormInterface $form;
    private ?Response $response;

    public function __construct() { }

    public function getForm(): ?FormInterface {
        return $this->form;
    }

    public function setForm(?FormInterface $form): static
    {
        $this->form = $form;
        return $this;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response): static
    {
        $this->response = $response;
        return $this;
    }

    public static function createInstanceOfObject(?FormInterface $form, ?Response $response): self
    {
        return (new FormResultData())->setForm($form)->setResponse($response);
    }
}