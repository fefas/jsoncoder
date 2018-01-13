<?php

namespace Fefas\Jsoncoder;

use StdClass;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class Asd
{
    private $a = 1;
    private $b = null;
}

class JsonTest extends TestCase
{
    /**
     * @test
     * @dataProvider jsonSamplesAsString
     */
    public function castToStringByMagicMethod(string $jsonString)
    {
        $json = Json::createFromString($jsonString);

        $toStringResult = $json->__toString();

        $this->assertEquals($jsonString, $toStringResult);
    }

    public function jsonSamplesAsString(): array
    {
        return [
            ['{"field1":"value1","field2":"value2"}'],
            ['[1,2,"asd"]'],
            ['1403'],
            ['null'],
            ['[1,2,"asd",{"field1":"value1","field2":"value2"}]'],
        ];
    }

    /**
     * @test
     */
    public function returnTheOriginalJsonString()
    {
        $jsonString = <<<JSON
{
    "field1": "value1",
    "field2": "value2"
}
JSON;
        $json = Json::createFromString($jsonString);

        $originalStringResult = $json->originalString();

        $this->assertEquals($jsonString, $originalStringResult);
    }

    /**
     * @test
     */
    public function minifyJsonString()
    {
        $jsonString = <<<JSON
{
    "field1": "value1",
    "field2": "value2"
}
JSON;
        $json = Json::createFromString($jsonString);

        $minifiedString = $json->__toString();

        $this->assertEquals('{"field1":"value1","field2":"value2"}', $minifiedString);
    }

    /**
     * @test
     * @dataProvider jsonStringsAndDecodedValues
     */
    public function decodeToPhpValue(string $jsonString, $expectedDecodedValue)
    {
        $json = Json::createFromString($jsonString);

        $decodedValue = $json->decode();

        $this->assertEquals($expectedDecodedValue, $decodedValue);
    }

    public function jsonStringsAndDecodedValues(): array
    {
        return [
            ['[1,2,"asd"]', [1,2,"asd"]],
            ['1403', 1403],
            ['null', null],
            [
                '{"field1":"value1","field2":"value2"}',
                [
                    'field1' => 'value1',
                    'field2' => 'value2',
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider phpValuesAndTheirExpectedJsonStrings
     */
    public function createFromScalarValuesAndArrays($value, string $expectedJsonString)
    {
        $json = Json::create($value);

        $jsonString = $json->__toString();

        $this->assertEquals($expectedJsonString, $jsonString);
    }

    public function phpValuesAndTheirExpectedJsonStrings(): array
    {
        return [
            [null, 'null'],
            [false, 'false'],
            [true, 'true'],
            [1403, '1403'],
            [1.403, '1.403'],
            ['1403', '"1403"'],
            [[1, 4, 0, 3], '[1,4,0,3]'],
            [['a' => 1, 'b' => 2], '{"a":1,"b":2}'],
            [['a' => true, 'b' => [1, 4.03, null]], '{"a":true,"b":[1,4.03,null]}'],
        ];
    }

    /**
     * @test
     */
    public function throwInvalidArgumentExceptionIfJsonStringIsInvalid()
    {
        $invalidJsonString = '"field":"value"}';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "The given string '$invalidJsonString' isn't a valid JSON"
        );

        Json::createFromString($invalidJsonString);
    }

    /**
     * @test
     */
    public function throwInvalidArgumentExceptionIfAnObjectIsGivenToCreate()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Cannot create a JSON from an object");

        $json = Json::create(new StdClass());
    }
}
