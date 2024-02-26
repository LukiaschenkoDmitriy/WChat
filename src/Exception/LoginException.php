<?php

namespace App\Exception;

class LoginException extends AbstractSecurityException
{
    public static $METHOD_IMPLEMENT_EXCEPTION = "Your implementation of the 'getUserData' method is incorrect, the returned data does not match the 'User' Entity, please check your 'User' Entity and the available fields.";
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function getMethodImplementException(): self {
        return new LoginException(self::$METHOD_IMPLEMENT_EXCEPTION);
    }
}