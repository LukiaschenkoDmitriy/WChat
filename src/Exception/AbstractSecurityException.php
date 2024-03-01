<?php

namespace App\Exception;

use Exception;

abstract class AbstractSecurityException extends Exception {
    public function __construct(string $message)
    {
        parent::__construct($this->buildMessage($message));
    }

    public function buildMessage($message): string {
        return "[WChat][Security][Exception] " + $message;
    }
}