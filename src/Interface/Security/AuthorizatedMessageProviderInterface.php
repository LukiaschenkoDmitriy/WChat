<?php

namespace App\Interface\Security;

/**
 * Interface AuthorizatedMessageProviderInterface
 * 
 * This interface defines a method for retrieving an authorized message.
 */
interface AuthorizatedMessageProviderInterface {
    /**
     * Retrieve the authorized message.
     * 
     * @return string The authorized message.
     */
    public function getAuhorizatedMessage(): string;
}
