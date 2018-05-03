<?php

use Abacus11\Collections\Exception\InvalidArgumentTypeException;
use Abacus11\Collections\Exception\TypeNotSetException;
use Faker\Factory;

if (class_exists('PHPUnit_Framework_TestCase')) {
    class TestCase extends PHPUnit_Framework_TestCase {}
} else {
    class TestCase extends PHPUnit\Framework\TestCase {}
}

class ArrayCollectionOf implements \ArrayAccess, \Abacus11\Collections\TypedCollection
{
    use \Abacus11\Collections\TypedArrayAccessTrait;
}

class SomeTestClass {}

class SomeTestClassExtended extends SomeTestClass {}

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
    public function basicTypedElementsProvider()
    {
        $faker = Factory::create();

        return [
            ['any', null],
            ['array', $faker->words],
            ['boolean', $faker->boolean],
            ['callable', $faker->randomElement([
                function() {},
                function($a) {return $a;},
                'strtolower',
                [$this, __FUNCTION__],
            ])],
            ['class', \DateTime::class],
            ['class', \DateTimeInterface::class],
            ['double', $faker->randomFloat()],
            ['integer', $faker->numberBetween(-2147483646)],
            ['number', $faker->randomElement([
                $faker->numberBetween(-2147483646),
                $faker->randomFloat(),
                $faker->numerify('########'),
                $faker->numerify('#####.###'),
                $faker->numerify('-#####'),
                $faker->numerify('-#####.##'),
            ])],
            ['object', new stdClass()],
            ['resource', fopen(__FILE__, 'rb')],
            ['string', $faker->words(3, true)],
            [__CLASS__, $this],
            [SomeTestClass::class, new SomeTestClassExtended()],
        ];
    }

    /**
     * Provides a list of mismatched collection type / value type / value triplets
     *
     * @return array
     */
    public function mismatchedBasicTypedElementsProvider()
    {
        $faker = Factory::create();

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
                ['integer', $faker->numberBetween(-2147483646)],
                ['double', $faker->randomFloat()],
                ['string_number', $faker->numerify('######')],
                ['string_number', $faker->numerify('##.###')],
                ['string_number', $faker->numerify('-#####')],
                ['string_number', $faker->numerify('-##.##')],
            ]),
            'string' => $faker->words(3, true),
            'integer' => $faker->numberBetween(-2147483646),
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
                if (!in_array($data_type, $aliases[$collection_type], true)) {
                    $data[] = [$collection_type, $data_type, $value];
                }
            }
        }

        return $data;
    }

    /**
     * Provides a list of matching type / sample value / value triplets
     *
     * @return array{string, mixed, mixed}
     */
    public function sampleTypedElementsProvider()
    {
        $faker = Factory::create();

        return [
            ['array', $faker->words, $faker->words],
            ['array', [], $faker->words],
            ['boolean', true, $faker->boolean],
            ['boolean', false, $faker->boolean],
            ['callback', function() {}, function($a) {return $a;}],
            ['callback', function() {}, [$this, __FUNCTION__]],
            ['callback', function($a) {return $a;}, 'strtolower'],
            ['double', $faker->randomFloat(), $faker->randomFloat()],
            ['double', 0.0, $faker->randomFloat()],
            ['integer', $faker->numberBetween(-2147483646), $faker->numberBetween(-2147483646)],
            ['integer', 0, $faker->numberBetween(-2147483646)],
            ['object', new SomeTestClass(), new SomeTestClassExtended()],
            ['object', $this, $this],
            ['resource', fopen(__FILE__, 'rb'), opendir(__DIR__)],
            ['string', $faker->words(3, true), $faker->words(3, true)],
            ['string', '', $faker->words(3, true)],
        ];
    }

    /**
     * Provides a list of mismatching sample type / sample / value type / value quads
     *
     * @return array
     */
    public function mismatchedSampleTypedElementsProvider()
    {
        $faker = Factory::create();

        $samples = [
            'array' => $faker->words,
            'boolean' => $faker->boolean,
            'callback' => function() {},
            'double' => $faker->randomFloat(),
            'integer' => $faker->numberBetween(-2147483646),
            'object' => (object) [],
            'resource' => fopen(__FILE__, 'rb'),
            'string' => $faker->words(3, true),
        ];

        $values = [
            'array' => $faker->words,
            'boolean' => $faker->boolean,
            'callback' => $faker->randomElement([
                ['object', function() {}],
                ['array', [$this, __FUNCTION__]],
                ['string', 'strtoupper'],
            ]),
            'double' => $faker->randomFloat(),
            'integer' => $faker->numberBetween(-2147483646),
            'object' => (object) [],
            'resource' => opendir(__DIR__),
            'string' => $faker->words(3, true),
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
    public function validJSONEncodedValuesProvider()
    {
        $faker = Factory::create();

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
    public function invalidJSONEncodedValuesProvider()
    {
        return [
            ['unquoted text'],
            ['{ "key": "<div style="color:black;">some text</div>" }'],
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
    public function testValueShouldBeValidForSameTypeCollection($type, $value)
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
    public function testValueShouldNotBeValidForMismatchedTypeCollection($type, $type_element, $value)
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
    public function testAddingValueToSameTypeCollectionShouldBePossible($type, $value)
    {
        $collection = (new ArrayCollectionOf())->setElementType($type);
        $collection[] = $value;
        $this->assertEquals($value, $collection[0]);
    }

    /**
     * @param mixed $value
     *
     * @dataProvider validJSONEncodedValuesProvider
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementType()
     */
    public function testAddingValidJSONToJSONCollectionShouldBePossible($value)
    {
        $collection = (new ArrayCollectionOf())->setElementType('json');
        $collection[] = $value;
        $this->assertEquals($value, $collection[0]);
    }

    /**
     * @param string $type
     * @param mixed $value
     *
     * @dataProvider basicTypedElementsProvider
     * @covers \Abacus11\Collections\TypedArrayAccessTrait::offsetSet()
     */
    public function testSettingValueToSameTypeCollectionShouldBePossible($type, $value)
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
    public function testSettingValidJSONToJSONCollectionShouldBePossible($value)
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
    public function testAddInvalidJSONToJSONCollectionShouldNotBePossible($value)
    {
        $collection = (new ArrayCollectionOf())->setElementType('json');

        $this->expectException(InvalidArgumentTypeException::class);
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
    public function testAddingWrongBasicTypeToCollectionShouldNotBePossible($type_collection, $type_element, $element)
    {
        $collection = (new ArrayCollectionOf())->setElementType($type_collection);

        $this->expectException(InvalidArgumentTypeException::class);
        $collection[] = $element;
    }

    public function testAddingElementToNonTypedCollectionShouldNotBePossible()
    {
        $collection = new ArrayCollectionOf();

        $this->expectException(TypeNotSetException::class);
        $collection[] = true;
    }

    /**
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementType()
     */
    public function testChangingTheTypeOfTypedCollectionShouldNotBePossible()
    {
        $collection = new ArrayCollectionOf();
        $collection->setElementType('string');

        $this->expectException(\Exception::class);
        $collection->setElementType('bool');
    }

    /**
     * @covers \Abacus11\Collections\TypedCollectionTrait::setElementTypeLike()
     */
    public function testUsingNullAsSampleTypeShouldNotBePossible()
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
    public function testAddingValidValueToLikeElementTypeCollectionShouldBePossible($type, $sample, $value)
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
    public function testAddingInvalidValueToLikeElementTypeCollectionShouldNotBePossible($sample_type, $sample, $value_type, $value)
    {
        $collection = (new ArrayCollectionOf())->setElementTypeLike($sample);

        $this->expectException(InvalidArgumentTypeException::class);
        $collection[0] = $value;
    }
}
