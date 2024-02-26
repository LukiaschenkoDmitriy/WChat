<?php

namespace App\Data;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class to hold form result data.
 */
class FormResultData
{
    private ?FormInterface $form;
    private ?Response $response;

    /**
     * Initializes a new instance of FormResultData.
     */
    public function __construct()
    {
    }

    /**
     * Get the form associated with the result.
     * 
     * @return FormInterface|null The form interface.
     */
    public function getForm(): ?FormInterface
    {
        return $this->form;
    }

    /**
     * Set the form associated with the result.
     * 
     * @param FormInterface|null $form The form interface.
     * @return static The current instance of FormResultData.
     */
    public function setForm(?FormInterface $form): static
    {
        $this->form = $form;
        return $this;
    }

    /**
     * Get the response associated with the result.
     * 
     * @return Response|null The response object.
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * Set the response associated with the result.
     * 
     * @param Response|null $response The response object.
     * @return static The current instance of FormResultData.
     */
    public function setResponse(?Response $response): static
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Create an instance of FormResultData with provided form and response.
     * 
     * @param FormInterface|null $form The form interface.
     * @param Response|null $response The response object.
     * @return FormResultData The created instance of FormResultData.
     */
    public static function createInstanceOfObject(?FormInterface $form, ?Response $response): self
    {
        return (new FormResultData())->setForm($form)->setResponse($response);
    }
}
