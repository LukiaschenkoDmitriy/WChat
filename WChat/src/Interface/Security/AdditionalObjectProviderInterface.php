<?php

namespace App\Interface\Security;

interface AdditionalObjectProviderInterface {
    public function getAdditionalObject(): mixed;
    public function setAdditionalObject(mixed $addObject);
    public function isValidAdditionalObject(mixed $object): bool;
}