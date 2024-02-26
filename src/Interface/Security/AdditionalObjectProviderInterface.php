<?php

namespace App\Interface\Security;

/**
 * Interface AdditionalObjectProviderInterface
 * 
 * This interface defines methods for providing additional objects and validating them.
 */
interface AdditionalObjectProviderInterface {
    /**
     * Retrieve the additional object.
     * 
     * @return mixed The additional object.
     */
    public function getAdditionalObject(): mixed;
    
    /**
     * Set the additional object.
     * 
     * @param mixed $addObject The additional object to set.
     * @return void
     */
    public function setAdditionalObject(mixed $addObject);
    
    /**
     * Check if the provided object is valid.
     * 
     * @param mixed $object The object to validate.
     * @return bool True if the object is valid, false otherwise.
     */
    public function isValidAdditionalObject(mixed $object): bool;
}
