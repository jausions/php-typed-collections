<?php

use Abacus11\Collections\ArrayOf;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for the ArrayOf and TypedCollectionTrait
 */
class ArrayOfTest extends TestCase
{
    /**
     * Provides a list of matching type / value pairs
     *
     * @return array
     */
    public function basicTypedElementsProvider(): array
    {
        $faker = \Faker\Factory::create();

        return [
            ['string', $faker->words(3, true)],
            ['integer', $faker->numberBetween(PHP_INT_MIN, PHP_INT_MAX)],
            ['double', $faker->randomFloat()],
            ['number', $faker->randomElement([
                $faker->numberBetween(PHP_INT_MIN, PHP_INT_MAX),
                $faker->randomFloat(),
                $faker->numerify('########'),
                $faker->numerify('#####.###'),
                $faker->numerify('-#####'),
                $faker->numerify('-#####.##'),
            ])],
            ['array', $faker->words],
            ['boolean', $faker->boolean],
            ['object', new stdClass()],
            ['callable', $faker->randomElement([
                function() {},
                function($a) {return $a;},
                'strtolower',
                [$this, __FUNCTION__],
            ])],
            ['resource', fopen(__FILE__, 'r')],
            [__CLASS__, $this],
            [__CLASS__, new class extends ArrayOfTest {}],
        ];
    }

    /**
     * Provides a list of mismatched collection type / value type / value triplets
     *
     * @return array
     */
    public function mismatchedBasicTypedElementsProvider(): array
    {
        $faker = \Faker\Factory::create();

        // Type => alias types
        $aliases = [
            'string' => ['string', 'text', 'string_number', 'json', 'string_callable'],
            'text' => ['string', 'text', 'string_number', 'json', 'string_callable'],
            'int' => ['int', 'integer'],
            'integer' => ['int', 'integer'],
            'float' => ['float', 'double'],
            'double' => ['float', 'double'],
            'number' => ['int', 'integer', 'float', 'double', 'number', 'string_number'],
            'boolean' => ['bool', 'boolean'],
            'bool' => ['bool', 'boolean'],
            'object' => ['object', 'closure', 'function', 'callable', 'callback'],
            'array' => ['array', 'array_callback'],
            'json' => ['json', 'string_number', 'string_callable'],
            'closure' => ['closure', 'function', 'callable', 'callback', 'string_callable', 'array_callback'],
            'function' => ['closure', 'function', 'callable', 'callback', 'string_callable', 'array_callback'],
            'callable' => ['closure', 'function', 'callable', 'callback', 'string_callable', 'array_callback'],
            'callback' => ['closure', 'function', 'callable', 'callback', 'string_callable', 'array_callback'],
        ];

        $values = [
            'json' => '{ "key": "value" }',
            'number' => $faker->randomElement([
                ['integer', $faker->numberBetween(PHP_INT_MIN, PHP_INT_MAX)],
                ['double', $faker->randomFloat()],
                ['string_number', $faker->numerify('######')],
                ['string_number', $faker->numerify('##.###')],
                ['string_number', $faker->numerify('-#####')],
                ['string_number', $faker->numerify('-##.##')],
            ]),
            'string' => $faker->words(3, true),
            'integer' => $faker->numberBetween(PHP_INT_MIN, PHP_INT_MAX),
            'double' => $faker->randomFloat(),
            'boolean' => $faker->boolean,
            'array' => $faker->words,
            'object' => new stdClass(),
            'callable' => $faker->randomElement([
                ['array_callback', [$this, __FUNCTION__]],
                ['callable', function() {}],
                ['callable', function($a) {return $a;}],
                ['string_callable', 'strtolower'],
            ]),
        ];

        $data = [];
        foreach (array_keys($values) as $collection_type) {
            foreach ($values as $data_type => $value) {
                if ($data_type === 'number' || $data_type === 'callable') {
                    $data_type = $value[0];
                    $value = $value[1];
                }
                if (!in_array($data_type, $aliases[$collection_type])) {
                    $data[] = [$collection_type, $data_type, $value];
                }
            }
        }

        return $data;
    }

    /**
     * Provides a list of matching type / sample value / value triplets
     *
     * @return array
     */
    public function sampleTypedElementsProvider(): array
    {
        $faker = \Faker\Factory::create();

        return [
            ['string', $faker->words(3, true), $faker->words(3, true)],
            ['string', '', $faker->words(3, true)],
            ['integer', $faker->numberBetween(PHP_INT_MIN, PHP_INT_MAX), $faker->numberBetween(PHP_INT_MIN, PHP_INT_MAX)],
            ['integer', 0, $faker->numberBetween(PHP_INT_MIN, PHP_INT_MAX)],
            ['double', $faker->randomFloat(), $faker->randomFloat()],
            ['double', 0.0, $faker->randomFloat()],
            ['array', $faker->words, $faker->words],
            ['array', [], $faker->words],
            ['boolean', true, $faker->boolean],
            ['boolean', false, $faker->boolean],
            ['object', new stdClass(), new class extends stdClass {}],
            ['object', $this, $this],
            ['resource', fopen(__FILE__, 'r'), opendir(__DIR__)],
            ['callback', function() {}, function($a) {return $a;}],
            ['callback', function() {}, [$this, __FUNCTION__]],
            ['callback', function($a) {return $a;}, 'strtolower'],
        ];
    }

    /**
     * Provides a list of mismatching sample type / sample / value type / value quads
     *
     * @return array
     */
    public function mismatchedSampleTypedElementsProvider(): array
    {
        $faker = \Faker\Factory::create();

        $samples = [
            'string' => $faker->words(3, true),
            'integer' => $faker->numberBetween(PHP_INT_MIN, PHP_INT_MAX),
            'double' => $faker->randomFloat(),
            'array' => $faker->words,
            'boolean' => $faker->boolean,
            'object' => new class extends stdClass {},
            'resource' => fopen(__FILE__, 'r'),
            'callback' => function() {},
        ];

        $values = [
            'string' => $faker->words(3, true),
            'integer' => $faker->numberBetween(PHP_INT_MIN, PHP_INT_MAX),
            'double' => $faker->randomFloat(),
            'array' => $faker->words,
            'boolean' => $faker->boolean,
            'object' => new class extends stdClass {},
            'resource' => opendir(__DIR__),
            'callback' => $faker->randomElement([
                ['object', function() {}],
                ['array', [$this, __FUNCTION__]],
                ['string', 'strtoupper'],
            ]),
        ];

        $data = [];

        foreach ($samples as $sample_type => $sample) {
            foreach ($values as $value_type => $value) {
                if ($sample_type === $value_type) {
                    continue;
                }
                if ($value_type === 'callback') {
                    $value_type = $value[0];
                    $value = $value[1];
                }
                if ($sample_type != $value_type) {
                    $data[] = [$sample_type, $sample, $value_type, $value];
                }
            }
        }

        return $data;
    }

    /**
     * Provides a list of valid JSON
     *
     * @return array
     */
    public function validJSONEncodedValuesProvider(): array
    {
        $faker = \Faker\Factory::create();

        $object = new stdClass();
        $object->prop_1 = $faker->words;
        $object->prop_2 = $faker->date;
        $object->prop_3 = new stdClass();

        return [
            ['null'],
            ['true'],
            ['false'],
            [json_encode(new stdClass())],
            [json_encode($faker->words)],
            [json_encode($object)],
            [json_encode($faker->randomNumber())],
            [json_encode($faker->randomFloat())],
        ];
    }

    /**
     * Provides a list of invalid JSON
     *
     * @return array
     */
    public function invalidJSONEncodedValuesProvider(): array
    {
        return [
            ['unquoted text'],
            ['{ "key": "<div class="coolCSS">some text</div>" }'],
            ['{ key: \'value\' }'],
            ['{ key: ["value", .5, 
	{ "test": 56, 
	\'test2\': [true, null] }
	]
}'],
        ];
    }

    /**
     * @param string $type
     * @param mixed $value
     *
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @dataProvider basicTypedElementsProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::isElementType()
     * @covers \Abacus11\Collections\ArrayOf::isElementType()
     */
    public function testValueIsValidForSameTypeCollection($type, $value): void
    {
        $collection = (new ArrayOf())->setElementType($type);
        $this->assertTrue($collection->isElementType($value));
    }

    /**
     * @param string $type
     * @param mixed $value
     *
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @dataProvider mismatchedBasicTypedElementsProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::isElementType()
     * @covers \Abacus11\Collections\ArrayOf::isElementType()
     */
    public function testValueIsInvalidForMismatchedTypeCollection($type, $type_element, $value): void
    {
        $collection = (new ArrayOf())->setElementType($type);
        $this->assertFalse($collection->isElementType($value));
    }

    /**
     * @param string $type
     * @param mixed $value
     *
     * @throws Exception
     * @throws TypeError
     * @throws AssertionError
     *
     * @dataProvider basicTypedElementsProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::add()
     * @covers \Abacus11\Collections\ArrayOf::add()
     */
    public function testCanAddValueToSameTypeCollection($type, $value): void
    {
        $collection = (new ArrayOf())->setElementType($type);
        $collection[] = $value;
        $this->assertEquals($value, $collection->first());
    }

    /**
     * @param mixed $value
     *
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @dataProvider validJSONEncodedValuesProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::add()
     * @covers \Abacus11\Collections\ArrayOf::add()
     */
    public function testCanAddValidJSONToJSONCollection($value): void
    {
        $collection = (new ArrayOf())->setElementType('json');
        $collection[] = $value;
        $this->assertEquals($value, $collection->first());
    }

    /**
     * @param mixed $value
     *
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @dataProvider invalidJSONEncodedValuesProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::add()
     * @covers \Abacus11\Collections\ArrayOf::add()
     */
    public function testCannotAddInvalidJSONToJSONCollection($value): void
    {
        $collection = (new ArrayOf())->setElementType('json');

        $this->expectException(\TypeError::class);
        $collection[] = $value;
    }

    /**
     * @param $type_collection
     * @param $type_element
     * @param mixed $element
     *
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @dataProvider mismatchedBasicTypedElementsProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::add()
     * @covers \Abacus11\Collections\ArrayOf::add()
     */
    public function testCannotAddWrongBasicTypeToCollection($type_collection, $type_element, $element): void
    {
        $collection = (new ArrayOf())->setElementType($type_collection);

        $this->expectException(\TypeError::class);
        $collection[] = $element;
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @covers \Abacus11\Collections\TypedCollectionTrait::add()
     * @covers \Abacus11\Collections\ArrayOf::add()
     */
    public function testCannotAddElementToNonTypedCollection(): void
    {
        $collection = new ArrayOf();

        $this->expectException(\Error::class);
        $collection[] = true;
    }

    /**
     * @param string $type
     * @param mixed $value
     *
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @dataProvider basicTypedElementsProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::set()
     * @covers \Abacus11\Collections\ArrayOf::set()
     */
    public function testCanAddValueWithKeyToSameTypeCollection($type, $value): void
    {
        $collection = (new ArrayOf())->setElementType($type);
        $collection['abc'] = $value;
        $this->assertEquals($value, $collection->first());
    }

    /**
     * @param mixed $value
     *
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @dataProvider validJSONEncodedValuesProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::set()
     * @covers \Abacus11\Collections\ArrayOf::set()
     */
    public function testCanAddValidJSONWithKeyToJSONCollection($value): void
    {
        $collection = (new ArrayOf())->setElementType('json');
        $collection['xyz'] = $value;
        $this->assertEquals($value, $collection->first());
    }

    /**
     * @param mixed $value
     *
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @dataProvider invalidJSONEncodedValuesProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::set()
     * @covers \Abacus11\Collections\ArrayOf::set()
     */
    public function testCannotAddInvalidJSONWithKeyToJSONCollection($value): void
    {
        $collection = (new ArrayOf())->setElementType('json');

        $this->expectException(\TypeError::class);
        $collection[123] = $value;
    }

    /**
     * @param $type_collection
     * @param $type_element
     * @param mixed $element
     *
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @dataProvider mismatchedBasicTypedElementsProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::set()
     * @covers \Abacus11\Collections\ArrayOf::set()
     */
    public function testCannotAddWrongBasicTypeWithKeyToCollection($type_collection, $type_element, $element): void
    {
        $collection = new ArrayOf();
        $collection->setElementType($type_collection);

        $this->expectException(\TypeError::class);
        $collection['456'] = $element;
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @covers \Abacus11\Collections\TypedCollectionTrait::set()
     * @covers \Abacus11\Collections\ArrayOf::set()
     */
    public function testCannotAddElementWithKeyToNonConfiguredCollection(): void
    {
        $collection = new ArrayOf();

        $this->expectException(\Error::class);
        $collection[0] = true;
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementType()
     * @covers \Abacus11\Collections\ArrayOf::setElementType()
     */
    public function testCannotChangeTheTypeOfNonEmptyCollection(): void
    {
        $collection = new ArrayOf();
        $collection->setElementType('string');
        $collection[] = (\Faker\Factory::create())->word;

        $this->expectException(\Exception::class);
        $collection->setElementType('bool');
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementType()
     * @covers \Abacus11\Collections\ArrayOf::setElementType()
     */
    public function testCannotChangeTheTypeOfTypedCollection(): void
    {
        $collection = new ArrayOf();
        $collection->setElementType('string');

        $this->expectException(\Exception::class);
        $collection->setElementType('bool');
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementTypeLike()
     * @covers \Abacus11\Collections\ArrayOf::setElementTypeLike()
     */
    public function testCannotUseNullAsSampleType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new ArrayOf())->setElementTypeLike(null);
    }

    /**
     * @param string $type
     * @param mixed $sample
     * @param mixed $value
     *
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @dataProvider sampleTypedElementsProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementTypeLike()
     * @covers \Abacus11\Collections\ArrayOf::setElementTypeLike()
     */
    public function testCanAddValidValueToLikeElementTypeCollection($type, $sample, $value): void
    {
        $collection = new ArrayOf();
        $collection->setElementTypeLike($sample)
            ->add($value);
        $this->assertEquals($value, $collection->first());
    }

    /**
     * @param string $sample_type
     * @param mixed $sample
     * @param string $value_type
     * @param mixed $value
     *
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @dataProvider mismatchedSampleTypedElementsProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementTypeLike()
     * @covers \Abacus11\Collections\ArrayOf::setElementTypeLike()
     */
    public function testCannotAddInvalidValueToLikeElementTypeCollection($sample_type, $sample, $value_type, $value): void
    {
        $collection = (new ArrayOf())->setElementTypeLike($sample);

        $this->expectException(\TypeError::class);
        $collection[] = $value;
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @covers \Abacus11\Collections\ArrayOf::__construct()
     */
    public function testFirstElementBlocksWrongInitialValues()
    {
        $this->expectException(\TypeError::class);
        new ArrayOf([1, '2', false, 2.5]);
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @covers \Abacus11\Collections\ArrayOf::__construct()
     */
    public function testFirstElementSetsTypeOfCollection()
    {
        $collection = new ArrayOf([1]);

        $this->expectException(\TypeError::class);
        $collection[] = 'xyz';
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @covers \Abacus11\Collections\ArrayOf::__construct()
     */
    public function testFirstElementLetsValidInitialValues()
    {
        $collection = new ArrayOf([0, 1, 2, 3, 4]);
        $this->assertEquals($collection[4], 4);
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     *
     * @covers \Abacus11\Collections\ArrayOf::__construct()
     */
    public function testCannotInitializeCollectionWithNull()
    {
        $this->expectException(\InvalidArgumentException::class);
        new ArrayOf([null, 'abc']);
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testArrayCollectionAcceptsOnlyArrays()
    {
        $collection = new \Abacus11\Collections\Arrays([[], [1, 2, 3], ['a', 'b', 'c']]);
        $this->expectException(\TypeError::class);
        $collection[] = false;
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testBooleanCollectionAcceptsOnlyBooleans()
    {
        $collection = new \Abacus11\Collections\Booleans([false, true, false]);
        $this->expectException(\TypeError::class);
        $collection[1] = 'abc';
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testCallableCollectionAcceptsOnlyCallables()
    {
        $collection = new \Abacus11\Collections\Callables([function(){}, [$this, __FUNCTION__], 'ucfirst']);
        $this->expectException(\TypeError::class);
        $collection['abc'] = 3.1415;
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testDoubleCollectionAcceptsOnlyDoubles()
    {
        $collection = new \Abacus11\Collections\Doubles([1.1, 2.0, -3.45]);
        $this->expectException(\TypeError::class);
        $collection['xyz'] = 3;
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testIntegerCollectionAcceptsOnlyIntegers()
    {
        $collection = new \Abacus11\Collections\Integers([1, 0, 2]);
        $this->expectException(\TypeError::class);
        $collection[2] = 'Hello world!';
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testJSONCollectionAcceptsOnlyJSON()
    {
        $collection = new \Abacus11\Collections\JSONs(['null', '{"key":"value"}']);
        $this->expectException(\TypeError::class);
        $collection['other'] = function() {};
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testNumberCollectionAcceptsOnlyNumbers()
    {
        $collection = new \Abacus11\Collections\Numbers(['123', -876543, 0, 9.876, '-4.5']);
        $this->expectException(\TypeError::class);
        $collection[] = new stdClass();
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testObjectCollectionAcceptsOnlyObjects()
    {
        $collection = new \Abacus11\Collections\Objects([new stdClass(), new class {}, function() {}, $this]);
        $this->expectException(\TypeError::class);
        $collection[] = 'text';
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testResourceCollectionAcceptsOnlyResources()
    {
        $collection = new \Abacus11\Collections\Resources([fopen(__FILE__, 'r'), opendir(__DIR__)]);
        $this->expectException(\TypeError::class);
        $collection[] = 123;
    }

    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testStringCollectionAcceptsOnlyStrings()
    {
        $collection = new \Abacus11\Collections\Strings(['abc', '']);
        $this->expectException(\TypeError::class);
        $collection[] = true;
    }
}
