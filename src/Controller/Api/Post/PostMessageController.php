<?php

namespace App\Controller\Api\Post;
use App\Entity\Message;
use App\Enum\MessageTypeEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PostMessageController extends AbstractController {
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private Security $security
    ) { }

    public function __invoke(Message $message): Message
    {
        $currentUser = $this->security->getUser();

        $message->setUser($currentUser);
        $message->setTime(date("H:i"));
        $message->setType(MessageTypeEnum::MESSAGE_TEXT_TYPE);

        $this->entityManagerInterface->persist($message);
        $this->entityManagerInterface->flush();

        return $message;
    }
}