<?php

namespace App\Voter;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Entity\File;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FileVoeter extends Voter {

    public const COLLECTION = "FILE_COLLECTION";
    public const GET = "FILE_GET";
    public const POST = "FILE_POST";
    public const PATCH = "FILE_PATCH";
    public const DELETE = "FILE_DELETE";

    private bool $isPaginator;
    private bool $isFile;
    private bool $hasCorrectAttribute;

    protected function supports(string $attribute, mixed $subject): bool
    {
        $this->isPaginator = $subject instanceof Paginator;
        $this->isFile = $subject instanceof File;
        $this->hasCorrectAttribute = in_array($attribute, [
            self::COLLECTION,
            self::GET,
            self::POST,
            self::PATCH,
            self::DELETE
        ]);

        return (($this->isPaginator || $this->isFile) && $this->hasCorrectAttribute) || self::POST == $attribute;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if ($this->isPaginator) {
            
        }

        return false;
    }
}