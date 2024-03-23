<?php

namespace App\Controller\Api\Collection\Custom;
use App\Entity\Chat;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CollectionFilesChatController extends AbstractController {
    public function __invoke(Chat $chat): Collection
    {
        return $chat->getFiles();
    }
}