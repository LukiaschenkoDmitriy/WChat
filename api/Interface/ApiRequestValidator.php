<?php

namespace Api\Interface;

use Symfony\Component\HttpFoundation\InputBag;

interface ApiRequestValidator {
    public function isValidRequest(InputBag $body): bool;
}