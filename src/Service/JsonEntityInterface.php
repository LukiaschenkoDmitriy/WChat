<?php

namespace App\Service;

interface JsonEntityInterface {
    public function toJson(): string;
}