<?php

namespace Fefas\Jsoncoder;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    /**
     * @test
     */
    public function allowCastingToStringByMagicMethod()
    {
        $json = Json::createFromString('{"field1":"value1","field2":"value2"}');

        $toStringResult = $json->__toString();

        $this->assertEquals('{"field1":"value1","field2":"value2"}', $toStringResult);
    }

    /**
     * @test
     */
    public function convertJsonValueToString()
    {
        $json = Json::createFromString('{"field1":"value1","field2":"value2"}');
        $expectedArray = [
            'field1' => 'value1',
            'field2' => 'value2',
        ];

        $toArrayResult = $json->toArray();

        $this->assertEquals($expectedArray, $toArrayResult);
    }

    /**
     * @test
     */
    public function buildItselfByGivenAnArray()
    {
        $expectedJson = Json::createFromString('{"field1":"value1","field2":"value2"}');

        $jsonFromArray = Json::createFromArray([
            'field1' => 'value1',
            'field2' => 'value2',
        ]);

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
