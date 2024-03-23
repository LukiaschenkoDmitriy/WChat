<?php

namespace App\Controller\Api\Collection;
use App\Entity\Member;
use App\Service\EntityManagerService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CollectionMemberController extends AbstractController {
    public function __construct(
        private EntityManagerService $entityManagerService,
        private EntityManagerInterface $entityManager,
        private Security $security
    ) { } 

    public function __invoke(): Collection
    {
        $userInt = $this->security->getUser()->getUserIdentifier();
        $user = $this->entityManagerService->getFullyUser($userInt);

        $members = new ArrayCollection();

        $user->getMembers()->exists(function (int $key, Member $member) use ($members) {
            $members->add($member);
        });

        return $members;

    }
}