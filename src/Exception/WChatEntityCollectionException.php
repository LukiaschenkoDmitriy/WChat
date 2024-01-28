<?php

namespace App\Exception;

use App\Enum\CollectionOperationEnum;
use Exception;

class WChatEntityCollectionException extends Exception {
    public function __construct(string $className, string $secondClassName, string $operation) 
    {
        parent::__construct($operation + "object " + $secondClassName + " in " + $className + " collection ");
    }
}