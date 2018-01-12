<?php

namespace Fefas\Jsoncoder;

use InvalidArgumentException;

class Json
{
    private $value;

    private function __construct(array $value)
    {
        $this->value = $value;
    }

    public function toArray(): array
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return json_encode($this->value);
    }

    public static function createFromString(string $string): self
    {
        $decodeToArray = true;
        $decodedArray = json_decode($string, $decodeToArray);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException(
                "The given string '$string' isn't a valid JSON"
            );
        }

        return new self($decodedArray);
    }

    public static function createFromArray(array $array): self
    {
        return new self($array);
    }
}
