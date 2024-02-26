<?php

namespace App\Interface\Security;

interface AuthorizatedMessageProviderInterface {
    public function getAuhorizatedMessage(): string;
}