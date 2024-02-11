<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChatDirectoryManager {

    public function __construct() {}

    public function getUniqueChatFolderName(): string
    {
        return "chat_".md5(uniqid());
    }

    public function createFolderChat(string $absolutePath)
    {
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($absolutePath)) {
            $fileSystem->mkdir($absolutePath);
        }
    }

    public function saveAvatarInDirectory(string $directory, UploadedFile $img)
    {
        $imgName = "avatar.".$img->guessExtension();    
        copy($img->getPathname(), $directory."/".$imgName);
    }
}