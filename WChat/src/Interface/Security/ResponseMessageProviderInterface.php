<?php

namespace App\Interface\Security;

interface ResponseMessageProviderInterface {
    public function getCorrectResponseMessage(): string;
    public function getWrongResponseMessage(): string;
}