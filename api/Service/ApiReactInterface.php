<?php

namespace Api\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

interface ApiReactInterface {
    public function fillJsonReponseOfData(array $array): JsonResponse;
}