# Typed Collections

Type hinting is evolving but PHP 7 still does not currently provide
a way to define the type of the elements of an array.

This library is built on top of Doctrine/Collections to enforce type
checking on elements added to a collection. We could also call them
strongly typed arrays.

The aim is to leverage type hinting to help prevent bugs, or, at the very
least, detect them earlier in the development cycle.

For the purpose of this library, the term *type* is used loosely to
refer to built-in PHP types, classes, and even application-domain types.

## Installation

```sh
composer require jausions/php-typed-collections
```

In the examples below, the `require 'vendor/autoload.php';` is implied.

## Type Defined by Initial Value

The first element passed to the constructor determines the criteria for
the elements that come after it.

```php
<?php
use Abacus11\Collections\TypedArrayCollection;

$int_array = new TypedArrayCollection([1, 2]);      // Okay
$int_array = new TypedArrayCollection([1, '2']);    // Not okay - throws \TypeError
$int_array = new TypedArrayCollection([null, 1]);   // Not okay - throws \InvalidArgumentException
```

## Type Defined by a Sample Value

The element validation is done against the type of a sample value.

```php
<?php
use Abacus11\Collections\TypedArrayCollection;

$sample = 1;
$int_array = (new TypedArrayCollection())->setElementTypeLike($sample);

$int_array[] = 2;                   // Okay
$int_array[] = true;                // Not okay - throws \TypeError exception

class SomeClass {}

$sample = new SomeClass();
$int_array = (new TypedArrayCollection())->setElementTypeLike($sample);

$int_array[] = new SomeClass();     // Okay
$int_array[] = new stdClass();      // Not okay - throws \TypeError exception
```

## Type Defined by a Closure

The elements added to the collection can be checked with a closure:

```php
<?php
use Abacus11\Collections\TypedArrayCollection;

$positive_int = (new TypedArrayCollection())->setElementType(function ($value) {
    if (!is_integer($value)) {
        return false;
    }
    return ($value >= 0);
});

$positive_int['apples'] = 0;      // Okay
$positive_int['oranges'] = 10;    // Okay
$positive_int['bananas'] = -5;    // Not okay - throws \TypeError exception
```

## Type Defined by a Class Name

Objects added to the collection can be checked against a class name:

```php
<?php

use Abacus11\Collections\TypedArrayCollection;

class A {}

class B {}

class AA extends A {}

$some_a = (new TypedArrayCollection())->setElementType(A::class);

$some_a[] = new A();    // Okay
$some_a[] = new AA();   // Okay
$some_a[] = new B();    // Not okay - throws \TypeError exception
```

## Built-In Library Types

Apart from a closure or a class name, the `setElementType()` method also
accepts the following values:

- `array`
- `boolean`
- `callable`
- `double`
- `integer`
- `number`
- `json`
- `object`
- `resource`
- `string`

```php
<?php
use Abacus11\Collections\TypedArrayCollection;

$int_array = (new TypedArrayCollection())->setElementType('integer');

$int_array[] = 1;     // Okay
$int_array[] = '1';   // Not okay - throws \TypeError exception
```

## Built-In Collections

Several typed collections are predefined:

- `CollectionOfArrays`
- `CollectionOfBooleans`
- `CollectionOfCallables`
- `CollectionOfDoubles`
- `CollectionOfIntegers`
- `CollectionOfNumbers`
- `CollectionOfJSONs`
- `CollectionOfObjects`
- `CollectionOfResources`
- `CollectionOfStrings`

```php
<?php

use Abacus11\Collections\CollectionOfIntegers;

$integers = new CollectionOfIntegers([1, 2, 3, 0, -1]);
```

## Custom Type Collections

You can easily create collections by extending the base class or by
including the trait into your own implementation of the ArrayAccess
interface.

```php
<?php
use Abacus11\Collections\TypedArrayCollection;

class Vehicle
{
}

class Car extends Vehicle
{
    public $make;
    public $model;
    public $color;
    public $license_plate_number;
}

class Submarine extends Vehicle
{
    public $name;
}

class CollectionOfCars extends TypedArrayCollection
{
    /**
     * @param Car[] $elements
     */
    public function __construct(array $elements = []) {
        $this->setElementType(Car::class);
        parent::__construct($elements);
    }
}

class Parking
{
    /**
     * @var CollectionOfCars
     */
    protected $lot;

    public function __construct()
    {
        $this->lot = new CollectionOfCars();
    }

    public function enter(Vehicle $car)
    {
        $this->lot[] = $car;
    }

    /**
     * @return Car[] The collection of cars
     */
    public function getCars(): CollectionOfCars
    {
        return $this->lot;
    }

    //...
}

$my_car = new Car();
$my_car->model = 'T';
$my_car->make = 'Ford';
$my_car->color = 'Black';
$my_car->license_plate_number = 'MI-01234';

$my_sub = new Submarine();
$my_sub->name = 'Nautilus';

$parking = new Parking();
$parking->enter($my_car);   // Okay
$parking->enter($my_sub);   // Not okay - throws \TypeError exception
```

Remarks:
1. We could have type hinted the `enter()` method with the `Car` class instead
   of the `Vehicle` class. This would also have thrown a \TypeError exception.
2. I am aware that I mixed the types in the *docBlock* and the signature of
   the `getCars()` method. It is somewhat more legible and may help your IDE.
   However, the benefit may vary depending on your editor / IDE, and it may
   lead to confusion if trying to use some array function that expect a native
   `array` type.
