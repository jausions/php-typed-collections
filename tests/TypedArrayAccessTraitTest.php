<?php

use PHPUnit\Framework\TestCase;

class ArrayCollectionOf implements \ArrayAccess, \Abacus11\Collections\TypedCollection
{
    use \Abacus11\Collections\TypedArrayAccessTrait;
}

/**
 * Test cases for the TypedArrayAccessTrait
 */
class TypedArrayAccessTraitTest extends TestCase
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
            [__CLASS__, new class extends TypedArrayAccessTraitTest {}],
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
     * @dataProvider basicTypedElementsProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::isElementType()
     */
    public function testValueIsValidForSameTypeCollection($type, $value): void
    {
        $collection = (new ArrayCollectionOf())->setElementType($type);
        $this->assertTrue($collection->isElementType($value));
    }

    /**
     * @param string $type
     * @param mixed $value
     *
     * @dataProvider mismatchedBasicTypedElementsProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::isElementType()
     */
    public function testValueIsInvalidForMismatchedTypeCollection($type, $type_element, $value): void
    {
        $collection = (new ArrayCollectionOf())->setElementType($type);
        $this->assertFalse($collection->isElementType($value));
    }

    /**
     * @param string $type
     * @param mixed $value
     *
     * @dataProvider basicTypedElementsProvider
     * @covers \Abacus11\Collections\TypedArrayAccessTrait::offsetSet()
     */
    public function testCanAddValueToSameTypeCollection($type, $value): void
    {
        $collection = (new ArrayCollectionOf())->setElementType($type);
        $collection[0] = $value;
        $this->assertEquals($value, $collection[0]);
    }

    /**
     * @param mixed $value
     *
     * @dataProvider validJSONEncodedValuesProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementType()
     */
    public function testCanAddValidJSONToJSONCollection($value): void
    {
        $collection = (new ArrayCollectionOf())->setElementType('json');
        $collection[0] = $value;
        $this->assertEquals($value, $collection[0]);
    }

    /**
     * @param mixed $value
     *
     * @dataProvider invalidJSONEncodedValuesProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementType()
     */
    public function testCannotAddInvalidJSONToJSONCollection($value): void
    {
        $collection = (new ArrayCollectionOf())->setElementType('json');

        $this->expectException(\TypeError::class);
        $collection[] = $value;
    }

    /**
     * @param $type_collection
     * @param $type_element
     * @param mixed $element
     *
     * @dataProvider mismatchedBasicTypedElementsProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementType()
     */
    public function testCannotAddWrongBasicTypeToCollection($type_collection, $type_element, $element): void
    {
        $collection = (new ArrayCollectionOf())->setElementType($type_collection);

        $this->expectException(\TypeError::class);
        $collection[] = $element;
    }

    public function testCannotAddElementToNonTypedCollection(): void
    {
        $collection = new ArrayCollectionOf();

        $this->expectException(\Error::class);
        $collection[] = true;
    }

    /**
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementType()
     */
    public function testCannotChangeTheTypeOfTypedCollection(): void
    {
        $collection = new ArrayCollectionOf();
        $collection->setElementType('string');

        $this->expectException(\Exception::class);
        $collection->setElementType('bool');
    }

    /**
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementTypeLike()
     */
    public function testCannotUseNullAsSampleType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new ArrayCollectionOf())->setElementTypeLike(null);
    }

    /**
     * @param string $type
     * @param mixed $sample
     * @param mixed $value
     *
     * @dataProvider sampleTypedElementsProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementTypeLike()
     */
    public function testCanAddValidValueToLikeElementTypeCollection($type, $sample, $value): void
    {
        $collection = (new ArrayCollectionOf())->setElementTypeLike($sample);
        $collection[0] = $value;
        $this->assertEquals($value, $collection[0]);
    }

    /**
     * @param string $sample_type
     * @param mixed $sample
     * @param string $value_type
     * @param mixed $value
     *
     * @dataProvider mismatchedSampleTypedElementsProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementTypeLike()
     */
    public function testCannotAddInvalidValueToLikeElementTypeCollection($sample_type, $sample, $value_type, $value): void
    {
        $collection = (new ArrayCollectionOf())->setElementTypeLike($sample);

        $this->expectException(\TypeError::class);
        $collection[0] = $value;
    }
}
