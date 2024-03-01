<?php

namespace App\Service;

use App\Entity\JWToken;
use App\Entity\User;
use App\Interface\JWTDatabaseProviderInterface;
use App\Repository\JWTokenRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Class JWTService
 * 
 * Service for generating and managing JWT tokens.
 */
class JWTService implements JWTDatabaseProviderInterface {

    private string $domainName = "127.0.0.1";

    private string $JWTSercretKey;
    private EntityManagerInterface $entityManagerInterface;
    private UserRepository $userRepository;
    private JWTokenRepository $jwTokenRepository;
    
    /**
     * JWTService constructor.
     * 
     * @param string $JWTSecretKey The secret key used for JWT encoding.
     * @param EntityManagerInterface $entityManagerInterface The entity manager for database operations.
     */
    public function __construct(
        string $JWTSecretKey, 
        EntityManagerInterface $entityManagerInterface,
        UserRepository $userRepository,
        JWTokenRepository $jwTokenRepository) 
    {
        $this->userRepository = $userRepository;
        $this->jwTokenRepository = $jwTokenRepository;
        $this->JWTSercretKey = $JWTSecretKey;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    /**
     * Get JWT token for a user. If the user has a token in the database, return that token,
     * otherwise generate a new token.
     * 
     * @param string $userInt The user identifier.
     * @return string The JWT token.
     */
    public function getToken(string $userInt, string $time): string
    {
        if ($this->isUserHasToken($userInt)) return $this->getTokenByEmail($userInt)->getJwt();
        return $this->generateToken($userInt, $time)->getJwt();
    }

    public function decodeToken(string $token)
    {
        try {
            return JWT::decode($token, new Key($this->JWTSercretKey, 'HS512'));
        } catch ( ExpiredException $exception ) {
            $this->deleteJwtTokenInDatabase($token);
            return null;
        }
    }

    public function deleteJwtTokenInDatabase(string $token): bool {
        $tokenEntity = $this->jwTokenRepository->findOneBy(["jwt" => $token]);;

        if ($tokenEntity == null) return false;
        
        $this->entityManagerInterface->remove($tokenEntity);
        $this->entityManagerInterface->flush();

        return true;
    }

    public function getUserByToken(string $token): ?User
    {
        $tokenEntity = $this->getTokenByJwt($token);
        return $this->userRepository->findOneBy(["email" => $tokenEntity->getEmail()]);
    }

    /**
     * Retrieve token for a user from the database.
     * 
     * @param string $userInt The user identifier.
     * @return JWToken|null The JWT token entity, or null if not found.
     */
    public function getTokenByEmail(string $email): ?JWToken
    {
        return $this->jwTokenRepository->findOneBy(["email" => $email]);
    }

    public function existToken(string $token): bool
    {
        return $this->getTokenByJwt($token) != null;
    }

    public function getTokenByJwt(string $token): ?JWToken
    {
        return $this->jwTokenRepository->findOneBy(["jwt" => $token]);
    }

    /**
     * Check if a user has a token in the database.
     * 
     * @param string $userInt The user identifier.
     * @return bool True if the user has a token, false otherwise.
     */
    public function isUserHasToken(string $userInt): bool
    {
        return !is_null($this->getTokenByEmail($userInt));
    }

    /**
     * Generate a JWT token for a user and save it in the database.
     * 
     * @param string $email The user's email.
     * @param string $shiftOfTimeExpire The time shift for token expiration.
     * @return JWToken The generated JWT token entity.
     */
    public function generateToken(string $email, string $shiftOfTimeExpire): JWToken {
        $jwToken = new JWToken();
        $token = JWT::encode($this->getRequestData($email, $shiftOfTimeExpire), $this->JWTSercretKey, "HS512");
        $jwToken->setJwt($token ? $token : "");
        $jwToken->setEmail($email);

        $this->saveTokenInDatabase($jwToken);
        
        return $jwToken;
    }

    /**
     * Save the JWT token in the database.
     * 
     * @param JWToken $jwToken The JWT token entity.
     * @return void
     */
    public function saveTokenInDatabase(JWToken $jwToken): void 
    {
        $this->entityManagerInterface->persist($jwToken);
        $this->entityManagerInterface->flush();
    }

    /**
     * Get the request data for token generation.
     * 
     * @param string $email The user's email.
     * @param string $shiftOfTimeExpire The time shift for token expiration.
     * @return array The request data for token generation.
     */
    public function getRequestData(string $email, string $shiftOfTimeExpire): array {
        $currentDate = new DateTimeImmutable();
        $expireAt = $currentDate->modify($shiftOfTimeExpire)->getTimestamp();

        return [
            "iat" => $currentDate->getTimestamp(),
            "iss" => $this->domainName,
            "hbf" => $currentDate->getTimestamp(),
            "exp" => $expireAt,
            "email" => $email
        ];
    }
}
