<?php

namespace App\Service;

use Doctrine\Common\Collections\Collection;

interface JsonConverterInterface {
    public function toJson(): string;
}

abstract class ConverterArrayToJson {
    public static function convert(array $array): string
    {
        $elements = [];
        foreach ($array as $element) {
            $elements[] = $element->toJson();
        }

        return json_encode($elements);
    }
}