<?php

namespace Fefas\Jsoncoder;

use StdClass;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

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

    /**
     * @test
     * @dataProvider jsonsToCompare
     */
    public function compareItselfWithAnotherJsonReturningTrueOrFalse(
        Json $json,
        Json $jsonToCompare,
        bool $expectedResult
    ) {
        $result = $json->isEqualTo($jsonToCompare);

        $this->assertSame($expectedResult, $result);
    }

    public function jsonsToCompare(): array
    {
        return [
            [Json::createFromString('[1,2,3]'), Json::createFromString('[1,2,3]'), true],
            [Json::createFromString('[3,1,2]'), Json::createFromString('[1,2,3]'), false],
            [Json::createFromString('[1,2,3]'), Json::createFromString('[1,3]'), false],
            [Json::createFromString('null'), Json::createFromString('null'), true],
            [Json::createFromString('null'), Json::createFromString('false'), false],
            [Json::createFromString('false'), Json::createFromString('null'), false],
            [Json::createFromString('[]'), Json::createFromString('false'), false],
            [Json::createFromString('false'), Json::createFromString('[]'), false],
            [Json::createFromString('[]'), Json::createFromString('null'), false],
            [Json::createFromString('null'), Json::createFromString('[]'), false],
            [Json::createFromString('true'), Json::createFromString('true'), true],
            [Json::createFromString('{"a":1,"b":2}'), Json::createFromString('{"a":1,"b":2}'), true],
            [Json::createFromString('{"a":1,"b":2}'), Json::createFromString('{"b":2,"a":1}'), true],
            [Json::createFromString('{"a":1,"b":2}'), Json::createFromString('{"b":2}'), false],
            [Json::createFromString('{"a":[1,2,{"b":1}]}'), Json::createFromString('{"a":[1,2,{"b":1}]}'), true],
            [Json::createFromString('{"a":[1,2,{"b":1,"a":2}]}'), Json::createFromString('{"a":[1,2,{"a":2,"b":1}]}'), true],

            [Json::create([1,2,3]), Json::create([1,2,3]), true],
            [Json::create([3,1,2]), Json::create([1,2,3]), false],
            [Json::create([1,2,3]), Json::create([1,3]), false],
            [Json::create(null), Json::create(null), true],
            [Json::create(null), Json::create(false), false],
            [Json::create(false), Json::create(null), false],
            [Json::create([]), Json::create(false), false],
            [Json::create(false), Json::create([]), false],
            [Json::create([]), Json::create(null), false],
            [Json::create(null), Json::create([]), false],
            [Json::create(true), Json::create(true), true],
            [Json::create(['a' => 1, 'b' => 2]), Json::create(['a' => 1, 'b' => 2]), true],
            [Json::create(['a' => 1, 'b' => 2]), Json::create(['b' => 2, 'a' => 1]), true],
            [Json::create(['a' => 1, 'b' => 2]), Json::create(['b' => 2]), false],
            [Json::create(['a' => [1, 2, ['b' => 1]]]), Json::create(['a' => [1, 2, ['b' => 1]]]), true],
            [Json::create(['a' => [1, 2, ['b' => 1, 'a' => 2]]]), Json::create(['a' => [1, 2, ['a' => 2, 'b' => 1]]]), true],
        ];
    }
}
