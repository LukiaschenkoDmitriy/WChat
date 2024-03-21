<?php

namespace App\Voter\Abstract;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Service\EntityManagerService;
use App\Voter\Interface\ResourceVoterInterface;
use App\Voter\Object\SubjectVoterTags;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class AbstractWChatVoter extends Voter implements ResourceVoterInterface {

    protected bool $isPaginator;
    protected bool $isSubjectSupports;
    protected bool $hasCorrectAttribute;

    public function __construct(
        protected Security $security,
        protected EntityManagerService $entityManagerService
    ) { }

    protected function supports(string $attribute, mixed $subject): bool
    {
        $this->isPaginator = $subject instanceof Paginator;
        $this->isSubjectSupports = $this->isSubjectSupports($subject);

        $voterTags = $this->getSubjectVoterTags();

        $this->hasCorrectAttribute = in_array($attribute, [
            $voterTags->COLLECTION,
            $voterTags->GET,
            $voterTags->POST,
            $voterTags->PATCH,
            $voterTags->DELETE
        ]);

        return (( $this->isPaginator || $this->isSubjectSupports ) && $this->hasCorrectAttribute) || $attribute == $voterTags->POST;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->entityManagerService->getFullyUser($token->getUser()->getUserIdentifier());

        if ($this->isPaginator) {
            return $this->hasGetCollectionAccess($user, $subject);
        }

        $voterTags = $this->getSubjectVoterTags();

        if ($this->isSubjectSupports) {
            switch ($attribute) {
                case $voterTags->GET:
                    return $this->hasGetAccess($user, $subject);
                case $voterTags->PATCH:
                    return $this->hasPatchAccess($user, $subject);
                case $voterTags->DELETE:
                    return $this->hasDeleteAccess($user, $subject);
            }
        }

        if ($attribute == $voterTags->POST) return $this->hasPostAccess($user);

        return false;
    }

    abstract public function isSubjectSupports(mixed $subject): bool;
    abstract function getSubjectVoterTags(): SubjectVoterTags;
}