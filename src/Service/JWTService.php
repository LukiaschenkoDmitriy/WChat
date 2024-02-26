<?php

namespace App\Service;

use DateTimeImmutable;
use Firebase\JWT\JWT;

class JWTService {

    private string $domainName = "127.0.0.1";
    private string $JWTSercretKey;
    
    public function __construct(string $JWTSecretKey) {
        $this->JWTSercretKey = $JWTSecretKey;
    }

    public function generateToken(array $userData, string $shiftOfTimeExpire): string {
        return JWT::encode($this->getRequestData($userData, $shiftOfTimeExpire), $this->JWTSercretKey, "HS512");
    }

    public function getRequestData(array $userData, string $shiftOfTimeExpire):array {
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

