<?php

namespace Fefas\Jsoncoder;

use PHPUnit\Framework\TestCase;

class JsonIsEqualToTest extends TestCase
{
    /**
     * @test
     * @dataProvider jsonsToCompare
     */
    public function checkIfIsEqualToAnotherJson(Json $aJson, Json $anotherJson, bool $isEqual)
    {
        $this->assertSame($isEqual, $aJson->isEqualTo($anotherJson));
    }

    public function jsonsToCompare(): array
    {
        return [
            [
                Json::createFromString('[1,2,3]'),
                Json::createFromString('[1,2,3]'),
                true,
            ],
            [
                Json::createFromString('[3,1,2]'),
                Json::createFromString('[1,2,3]'),
                false,
            ],
            [
                Json::createFromString('[1,2,3]'),
                Json::createFromString('[1,3]'),
                false,
            ],
            [
                Json::createFromString('null'),
                Json::createFromString('null'),
                true,
            ],
            [
                Json::createFromString('null'),
                Json::createFromString('false'),
                false,
            ],
            [
                Json::createFromString('false'),
                Json::createFromString('null'),
                false,
            ],
            [
                Json::createFromString('[]'),
                Json::createFromString('false'),
                false,
            ],
            [
                Json::createFromString('false'),
                Json::createFromString('[]'),
                false,
            ],
            [
                Json::createFromString('[]'),
                Json::createFromString('null'),
                false,
            ],
            [
                Json::createFromString('null'),
                Json::createFromString('[]'),
                false,
            ],
            [
                Json::createFromString('true'),
                Json::createFromString('true'),
                true,
            ],
            [
                Json::createFromString('{"a":1,"b":2}'),
                Json::createFromString('{"a":1,"b":2}'),
                true,
            ],
            [
                Json::createFromString('{"a":1,"b":2}'),
                Json::createFromString('{"b":2,"a":1}'),
                true,
            ],
            [
                Json::createFromString('{"a":1,"b":2}'),
                Json::createFromString('{"b":2}'),
                false,
            ],
            [
                Json::createFromString('{"a":[1,2,{"b":1}]}'),
                Json::createFromString('{"a":[1,2,{"b":1}]}'),
                true,
            ],
            [
                Json::createFromString('{"a":[1,2,{"b":1,"a":2}]}'),
                Json::createFromString('{"a":[1,2,{"a":2,"b":1}]}'),
                true,
            ],
            [
                Json::create([1,2,3]),
                Json::create([1,2,3]),
                true,
            ],
            [
                Json::create([3,1,2]),
                Json::create([1,2,3]),
                false,
            ],
            [
                Json::create([1,2,3]),
                Json::create([1,3]),
                false,
            ],
            [
                Json::create(null),
                Json::create(null),
                true,
            ],
            [
                Json::create(null),
                Json::create(false),
                false,
            ],
            [
                Json::create(false),
                Json::create(null),
                false,
            ],
            [
                Json::create([]),
                Json::create(false),
                false,
            ],
            [
                Json::create(false),
                Json::create([]),
                false,
            ],
            [
                Json::create([]),
                Json::create(null),
                false,
            ],
            [
                Json::create(null),
                Json::create([]),
                false,
            ],
            [
                Json::create(true),
                Json::create(true),
                true,
            ],
            [
                Json::create(['a' => 1, 'b' => 2]),
                Json::create(['a' => 1, 'b' => 2]),
                true,
            ],
            [
                Json::create(['a' => 1, 'b' => 2]),
                Json::create(['b' => 2, 'a' => 1]),
                true,
            ],
            [
                Json::create(['a' => 1, 'b' => 2]),
                Json::create(['b' => 2]),
                false,
            ],
            [
                Json::create(['a' => [1, 2, ['b' => 1]]]),
                Json::create(['a' => [1, 2, ['b' => 1]]]),
                true,
            ],
            [
                Json::create(['a' => [1, 2, ['b' => 1, 'a' => 2]]]),
                Json::create(['a' => [1, 2, ['a' => 2, 'b' => 1]]]),
                true,
            ],
        ];
    }
}
