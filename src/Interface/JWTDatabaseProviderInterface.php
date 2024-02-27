<?php

namespace App\Interface;

use App\Entity\JWToken;

/**
 * Interface JWTDatabaseProviderInterface
 * 
 * This interface defines methods for interacting with a database related to JWT tokens.
 */
interface JWTDatabaseProviderInterface
{
    public function existToken(string $token): bool;

    /**
     * Check if a user has a token in the database.
     * 
     * @param string $userInt The user identifier.
     * @return bool True if the user has a token, false otherwise.
     */
    public function isUserHasToken(string $userInt): bool;
    
    /**
     * Retrieve token for a user from the database.
     * 
     * @param string $userInt The user identifier.
     * @return JWToken|null The JWT token entity, or null if not found.
     */
    public function getTokenByEmail(string $email): ?JWToken;

    public function getTokenByJwt(string $token): ?JWToken;

    public function deleteJwtTokenInDatabase(string $token): bool;
    
    /**
     * Save the JWT token in the database.
     * 
     * @param JWToken $jwToken The JWT token entity.
     * @return void
     */
    public function saveTokenInDatabase(JWToken $jwToken): void;
}
