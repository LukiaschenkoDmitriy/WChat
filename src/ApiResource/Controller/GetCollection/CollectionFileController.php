<?php

namespace Api\Controller\GetCollection;

use App\Service\EntityManagerService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CollectionFileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private Security $security,
        private EntityManagerService $entityManagerService
    ) { }

    public function __invoke(): Collection
    {
        $userIdentifier = $this->security->getUser()->getUserIdentifier();
        $user = $this->entityManagerService->getFullyUser($userIdentifier);

        $files = new ArrayCollection();

        $user->getMembers()->filter(function ($member) use ($files) {
            $member->getChat()->getFiles()->filter(function ($file) use ($files) {
                $files->add($file);
            });
        });

        return $files;
    }
}