# Typed Collections

Type hinting is evolving but PHP still does not currently provide
a way to define the type of the elements of an array.

This library provides traits that can be used to implement type checking.

If you do not wish to implement anything, simply use one of the prebuilt
solutions below:

- For Doctrine collections, see this package: [jausions/php-typed-doctrine-collections](https://github.com/jausions/php-typed-doctrine-collections).

For the purpose of this library, the term *type* is used loosely to
refer to built-in PHP types, classes, and even application-domain types.


## Installation

```sh
composer require jausions/php-typed-collections
```

In the examples below, the `require 'vendor/autoload.php';` is implied.


## Simplistic Example

This example only implements the [ArrayAccess PHP Predefined Interface](http://php.net/manual/en/class.arrayaccess.php).

```php
<?php

use Abacus11\Collections\TypedCollection;
use Abacus11\Collections\TypedArrayAccessTrait;

class ArrayOf implements \ArrayAccess, TypedCollection
{
    use TypedArrayAccessTrait;
}
```


## Type Defined by a Sample Value

The element validation is done against the type of a sample value.

```php
<?php
// With the ArrayOf class defined above

$sample = 1;
$int_array = (new ArrayOf())->setElementTypeLike($sample);

$int_array[] = 2;           // Okay
$int_array[] = true;        // Not okay - throws an exception

class SomeClass {}

$sample = new SomeClass();
$some = (new ArrayOf())->setElementTypeLike($sample);

$some[] = new SomeClass();  // Okay
$some[] = new stdClass();   // Not okay - throws an exception
```


## Type Defined by a Closure

The elements added to the collection can be checked with a closure:

```php
<?php
// With the ArrayOf class defined above

$positive_int = (new ArrayOf())->setElementType(static function ($value) {
    if (!is_integer($value)) {
        return false;
    }

    return ($value >= 0);
});

$positive_int['apples'] = 0;        // Okay
$positive_int['oranges'] = 10;      // Okay
$positive_int['bananas'] = -5;      // Not okay - throws an exception
```


## Type Defined by a Class Name

Objects added to the collection can be checked against a class name:

```php
<?php
// With the ArrayOf class defined above

class A {}

class B {}

class AA extends A {}

$some_a = (new ArrayOf())->setElementType(A::class);

$some_a[] = new A();    // Okay
$some_a[] = new AA();   // Okay
$some_a[] = new B();    // Not okay - throws \TypeError exception
```


## Built-In Library Types

Apart from a closure or a class name, the `setElementType()` method also
accepts the following predefined values:

- `TypedCollection::OF_ANYTHING`
- `TypedCollection::OF_ARRAYS`
- `TypedCollection::OF_BOOLEANS`
- `TypedCollection::OF_CALLABLES`
- `TypedCollection::OF_CLASSES_AND_INTERFACES`
- `TypedCollection::OF_DOUBLES`
- `TypedCollection::OF_INTEGERS`
- `TypedCollection::OF_JSON`
- `TypedCollection::OF_NUMBERS`
- `TypedCollection::OF_OBJECTS`
- `TypedCollection::OF_PHP_RESOURCES`
- `TypedCollection::OF_STRINGS`

```php
<?php
// With the ArrayOf class defined above

use Abacus11\Collections\TypedCollection;

$int_array = (new ArrayOf())->setElementType(TypedCollection::OF_INTEGERS);

$int_array[] = 1;       // Okay
$int_array[] = '1';     // Not okay - throws an exception
```


## Checking a Value

If you want to know if a value would be accepted in the typed collection,
you can use the `isElementType()` method.

```php
<?php
// With the ArrayOf class defined above

$collection = (new ArrayOf())->setElementType('integer');

$value = 'abc';
if ($collection->isElementType($value)) {
    // Do something
}
```


## Custom Type Collections

You can easily create collections by extending the base class or by
including the trait into your own implementation of the ArrayAccess
interface.

> Remarks:
> 1. We could have type hinted the `enter()` method with the `Car` class instead
>    of the `Vehicle` class.
> 2. I am aware that I mixed the types in the *docBlock* and the signature of
>    the `getCars()` method. It is somewhat more legible and may help your IDE.
>    However, the benefit may vary depending on your editor / IDE, and it may
>    lead to confusion if trying to use some array function that expect a native
>    `array` type.

```php
<?php

interface Vehicle {}

class Car implements Vehicle
{
    public $make;
    public $model;
    public $color;
    public $license_plate_number;
}

class Submarine implements Vehicle
{
    public $name;
}

// With the ArrayOf class defined above
class Cars extends ArrayOf
{
    public function __construct() {
        $this->setElementType(Car::class);
    }
}

class Parking
{
    /** @var Cars */
    protected $lot;

    public function __construct()
    {
        $this->lot = new Cars();
    }

    public function enter(Vehicle $car)
    {
        $this->lot[] = $car;
    }

    /**
     * @return Car[] The collection of cars
     */
    public function getCars(): Cars
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
$parking->enter($my_car);       // Okay
$parking->enter($my_sub);       // Not okay - throws an exception
```
