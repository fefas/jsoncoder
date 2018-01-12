<?php

namespace Fefas\Jsoncoder;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    /**
     * @test
     */
    public function returnStringUsingMagicMethod()
    {
        $json = Json::createFromString('{"field":"value"}');

        $toStringResult = $json->__toString();

        $this->assertEquals('{"field":"value"}', $toStringResult);
    }

    /**
     * @test
     */
    public function returnValueAsArray()
    {
        $json = Json::createFromString('{"field":"value"}');

        $toArrayResult = $this->json->toArray();

        $this->assertEquals(['field' => 'value'], $toArrayResult);
    }

    /**
     * @test
     */
    public function buildFromAnArray()
    {
        $expectedJson = Json::createFromString('{"field":"value"}');

        $jsonFromArray = Json::createFromArray(['field' => 'value']);

        $this->assertEquals($expectedJson, $jsonFromArray);
    }

    /**
     * @test
     */
    public function throwInvalidArgumentExceptionIfTheGivenJsonStringIsInvalid()
    {
        $invalidJsonString = '"field":"value"}';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "The given string '$invalidJsonString' isn't a valid JSON"
        );

        Json::createFromString($invalidJsonString);
    }
}
