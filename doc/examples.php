<?php

require '../vendor/autoload.php';

use Abacus11\Collections\{
    TypedArrayAccessTrait,
    TypedCollection
};

## Very simplistic implementation of the ArrayAccess interface

class ArrayOf implements \ArrayAccess, TypedCollection
{
    use TypedArrayAccessTrait;
}


## Type Defined by a Sample Value

$sample = 1;
$int_array = (new ArrayOf())->setElementTypeLike($sample);

$int_array[] = 2;
try {
    $int_array[] = true;
} catch (\TypeError $e) {
    echo ' 1. '.$e->getMessage().PHP_EOL;
}

class SomeClass {}

$sample = new SomeClass();
$some = (new ArrayOf())->setElementTypeLike($sample);

$some[] = new SomeClass();
try {
    $some[] = new stdClass();
} catch (\TypeError $e) {
    echo ' 2. '.$e->getMessage().PHP_EOL;
}


## Type Defined by a Closure

// Use setElementType() method

$positive_int = (new ArrayOf())->setElementType(function ($value) {
    if (!is_integer($value)) {
        return false;
    }
    return ($value >= 0);
});

$positive_int['apples'] = 0;
$positive_int['oranges'] = 10;
try {
    $positive_int['bananas'] = -5;
} catch (\TypeError $e) {
    echo ' 3. '.$e->getMessage().PHP_EOL;
}


## Type Defined by a Class Name

class A {}

class B {}

class AA extends A {}

// Use the setElementType() method

$some_a = (new ArrayOf())->setElementType(A::class);

$some_a[] = new A();
$some_a[] = new AA();
try {
    $some_a[] = new B();
} catch (\TypeError $e) {
    echo ' 4. '.$e->getMessage().PHP_EOL;
}


## Built-In Library Types

// Use the setElementType() method

$int_array = (new ArrayOf())->setElementType('integer');

$int_array[] = 1;
try {
    $int_array[] = '1';
} catch (\TypeError $e) {
    echo ' 5. '.$e->getMessage().PHP_EOL;
}


## Custom Type Collections

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

class Cars extends ArrayOf
{
    public function __construct() {
        $this->setElementType(Car::class);
    }
}

class Parking
{
    /**
     * @var Cars
     */
    protected $lot;

    public function __construct()
    {
        $this->lot = new Cars([]);
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
$parking->enter($my_car);
try {
    $parking->enter($my_sub);
} catch (\TypeError $e) {
    echo ' 6. '.$e->getMessage().PHP_EOL;
}

