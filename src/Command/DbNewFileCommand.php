<?php

namespace App\Command;

use App\Entity\Chat;
use App\Entity\ChatFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'db:new-file',
    description: 'Add a new value of ChatFile in database table',
)]
class DbNewFileCommand extends Command
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
            ->addArgument('name', InputArgument::REQUIRED, 'File of name')
            ->addArgument('category', InputArgument::REQUIRED, 'File of category')
            ->addArgument('url', InputArgument::REQUIRED, 'Url of file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $chat = $this->entityManagerInterface->getRepository(Chat::class)->findOneBy(["id" => $input->getArgument('chat_id')]);

        $file = new ChatFile();
        $file
            ->setChat($chat)
            ->setName($input->getArgument("name"))
            ->setUrl($input->getArgument("url"))
            ->setKategory($input->getArgument("category"));

        $this->entityManagerInterface->persist($file);
        $this->entityManagerInterface->flush();

        $output->writeln("File ".$file->getName()." will be added in chat: ".$chat->getName());

        return Command::SUCCESS;
    }
}
