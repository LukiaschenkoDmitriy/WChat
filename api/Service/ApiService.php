<?php

namespace Api\Service;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Abstract base class for API services.
 */
abstract class ApiService {
    /**
     * Executes the API service with the given arguments.
     *
     * @param HeaderBag $args The arguments for the API service.
     * @return JsonResponse The JSON response.
     */
    public function execute(HeaderBag $args): JsonResponse {
        if (!$this->validate($args)) return $this->getWrongJsonResponse($args);
        return $this->getCorrectJsonResponse($args);
    }

    /**
     * Validates the arguments for the API service.
     *
     * @param HeaderBag $args The arguments to validate.
     * @return bool Returns true if the arguments are valid, otherwise false.
     */
    public abstract function validate(HeaderBag $args): bool;

    /**
     * Generates a JSON response for incorrect data.
     *
     * @param HeaderBag $args The arguments for the API service.
     * @return JsonResponse The JSON response for incorrect data.
     */
    public abstract function getWrongJsonResponse(HeaderBag $args): JsonResponse;

    /**
     * Generates a JSON response for correct data.
     *
     * @param HeaderBag $args The arguments for the API service.
     * @return JsonResponse The JSON response for correct data.
     */
    public abstract function getCorrectJsonResponse(HeaderBag $args): JsonResponse;
}
