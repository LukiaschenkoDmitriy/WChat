<?php

namespace App\Command;

use App\Entity\Chat;
use App\Entity\ChatFile;
use App\Entity\ChatMember;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'db:new-member',
    description: 'Add a new member in chat',
)]
class DbNewMemberCommand extends Command
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
            ->addArgument('role_id', InputArgument::REQUIRED, 'Role of user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $chat = $this->entityManagerInterface->getRepository(Chat::class)->findOneBy(["id" => $input->getArgument('chat_id')]);
        $user = $this->entityManagerInterface->getRepository(User::class)->findOneBy(["id" => $input->getArgument('user_id')]);

        $member = new ChatMember();
        $member
            ->setUser($user)
            ->setChat($chat)
            ->setRoleId($input->getArgument("role_id"));

        $this->entityManagerInterface->persist($member);
        $this->entityManagerInterface->flush();

        $output->writeln("New member ".$user->getEmail()." will be added in chat: ".$chat->getName());

        return Command::SUCCESS;
    }
}
