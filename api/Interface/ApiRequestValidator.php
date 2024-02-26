<?php

namespace Api\Interface;

use Symfony\Component\HttpFoundation\InputBag;

/**
 * Interface for validating API requests.
 */
interface ApiRequestValidator {
    /**
     * Validates the API request.
     *
     * @param InputBag $body The request body.
     * @return bool True if the request is valid, otherwise false.
     */
    public function isValidRequest(InputBag $body): bool;
}
