<?php

namespace App\Interface\Security;

/**
 * Interface ResponseMessageProviderInterface
 * 
 * This interface defines methods for providing response messages.
 */
interface ResponseMessageProviderInterface {
    /**
     * Retrieve the correct response message.
     * 
     * @return string The correct response message.
     */
    public function getCorrectResponseMessage(): string;
    
    /**
     * Retrieve the wrong response message.
     * 
     * @return string The wrong response message.
     */
    public function getWrongResponseMessage(): string;
}
