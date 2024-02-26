<?php

namespace App\Service;

use DateTimeImmutable;
use Firebase\JWT\JWT;

/**
 * Class JWTService
 * 
 * Service for generating JWT tokens.
 */
class JWTService {

    private string $domainName = "127.0.0.1";
    private string $JWTSercretKey;
    
    /**
     * JWTService constructor.
     * 
     * @param string $JWTSecretKey The secret key used for JWT encoding.
     */
    public function __construct(string $JWTSecretKey) {
        $this->JWTSercretKey = $JWTSecretKey;
    }

    /**
     * Generate a JWT token.
     * 
     * @param array $userData The user data to include in the token.
     * @param string $shiftOfTimeExpire The time shift for token expiration.
     * @return string The generated JWT token.
     */
    public function generateToken(array $userData, string $shiftOfTimeExpire): string {
        return JWT::encode($this->getRequestData($userData, $shiftOfTimeExpire), $this->JWTSercretKey, "HS512");
    }

    /**
     * Get the request data for token generation.
     * 
     * @param array $userData The user data to include in the token.
     * @param string $shiftOfTimeExpire The time shift for token expiration.
     * @return array The request data for token generation.
     */
    public function getRequestData(array $userData, string $shiftOfTimeExpire): array {
        $currentDate = new DateTimeImmutable();
        $expireAt = $currentDate->modify($shiftOfTimeExpire)->getTimestamp();

        return [
            "iat" => $currentDate->getTimestamp(),
            "iss" => $this->domainName,
            "hbf" => $currentDate->getTimestamp(),
            "exp" => $expireAt,
            "user_data" => $userData
        ];
    }
}
