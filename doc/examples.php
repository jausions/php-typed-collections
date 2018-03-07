<?php

require '../vendor/autoload.php';

use Abacus11\Collections\ArrayOf;

$int_array = (new ArrayOf())->setElementType('integer');

$int_array[] = 1;     // Okay
try {
    $int_array[] = '1';   // Not okay - throws \TypeError exception
} catch (\TypeError $e) {
    echo '1. '.$e->getMessage().PHP_EOL;
}

## Defining the Type by Example

$sample = 1;
$int_array = (new ArrayOf())->setElementTypeLike($sample);

$int_array[] = 2;     // Okay
try {
    $int_array[] = true;   // Not okay - throws \TypeError exception
} catch (\TypeError $e) {
    echo '2. '.$e->getMessage().PHP_EOL;
}

## Using a Closure

$positive_int = (new ArrayOf())->setElementType(function ($value) {
    if (!is_integer($value)) {
        return false;
    }
    return ($value >= 0);
});

$positive_int['apples'] = 0;      // Okay
$positive_int['oranges'] = 10;    // Okay
try {
    $positive_int['bananas'] = -5;    // Not okay - throws \TypeError exception
} catch (\TypeError $e) {
    echo '3. '.$e->getMessage().PHP_EOL;
}

## Using a Class Name

class A {}

class B {}

class AA extends A {}

$some_a = (new ArrayOf())->setElementType(A::class);

$some_a[] = new A();    // Okay
$some_a[] = new AA();   // Okay

try {
    $some_a[] = new B();    // Not okay - throws \TypeError exception
} catch (\TypeError $e) {
    echo '4. '.$e->getMessage().PHP_EOL;
}

## Validation With Initial Value

$int_array = new ArrayOf([1, 2]);     // Okay
try {
    $int_array = new ArrayOf([1, '2']);   // Not okay - throws \TypeError
} catch (\TypeError $e) {
    echo '5. '.$e->getMessage().PHP_EOL;
}

try {
    $int_array = new ArrayOf([null, 1]);   // Not okay - throws \TypeError
} catch (\InvalidArgumentException $e) {
    echo '6. '.$e->getMessage().PHP_EOL;
}

## Example

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
     * @var Cars
     */
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
$parking->enter($my_car);   // Okay
try {
    $parking->enter($my_sub);   // Not okay - throws \TypeError exception
} catch (\TypeError $e) {
    echo '7. '.$e->getMessage().PHP_EOL;
}
