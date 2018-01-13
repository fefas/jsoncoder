<?php

namespace Fefas\Jsoncoder;

use InvalidArgumentException;

class Json
{
    private $decodedValue;
    private $originalString;

    private function __construct($decodedValue, string $originalString = null)
    {
        $this->decodedValue = $decodedValue;
        $this->originalString = $originalString;
    }

    public function originalString(): ?string
    {
        return $this->originalString;
    }

    public function decode()
    {
        return $this->decodedValue;
    }

    public function __toString()
    {
        return json_encode($this->decodedValue);
    }

    public function isEqualTo(self $jsonToCompare): bool
    {
        $thisDecoded = $this->decode();
        $thatDecoded = $jsonToCompare->decode();

        if (!is_array($thisDecoded) || !is_array($thatDecoded)) {
            return $thisDecoded === $thatDecoded;
        }

        return $thisDecoded == $thatDecoded;
    }

    public static function create($value): self
    {
        if (is_object($value)) {
            throw new InvalidArgumentException("Cannot create a JSON from an object");
        }

        return new self($value);
    }

    public static function createFromString(string $jsonString): self
    {
        $decodedValue = self::decodeJsonString($jsonString);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException(
                "The given string '$jsonString' isn't a valid JSON"
            );
        }

        return new self($decodedValue, $jsonString);
    }

    private static function decodeJsonString(string $jsonString)
    {
        $decodeToAssoc = true;

        return json_decode($jsonString, $decodeToAssoc);
    }
}
