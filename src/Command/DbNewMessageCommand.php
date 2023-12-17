<?php

namespace App\Command;

use App\Entity\Chat;
use App\Entity\ChatFile;
use App\Entity\ChatMember;
use App\Entity\ChatMessage;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'db:new-message',
    description: 'Add a message in chat',
)]
class DbNewMessageCommand extends Command
{
    private EntityManagerInterface $entityManagerInterface;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('chat_id', InputArgument::REQUIRED, 'Id of chat')
            ->addArgument('user_id', InputArgument::REQUIRED, "Id of user")
            ->addArgument('message', InputArgument::REQUIRED, 'Message')
            ->addArgument("date", InputArgument::REQUIRED, "Date of message")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $chat = $this->entityManagerInterface->getRepository(Chat::class)->findOneBy(["id" => $input->getArgument('chat_id')]);
        $user = $this->entityManagerInterface->getRepository(User::class)->findOneBy(["id" => $input->getArgument('user_id')]);

        $message = new ChatMessage();
        $message
            ->setUser($user)
            ->setChat($chat)
            ->setMessage($input->getArgument("message"))
            ->setDate($input->getArgument("date"));

        $this->entityManagerInterface->persist($message);
        $this->entityManagerInterface->flush();

        $output->writeln("User: ".$user->getEmail()." added new message '".$message->getMessage()."' in chat: ".$chat->getName());

        return Command::SUCCESS;
    }
}
