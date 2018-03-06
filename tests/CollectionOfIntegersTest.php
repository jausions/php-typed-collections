<?php

use Abacus11\Collections\CollectionOfIntegers;
use PHPUnit\Framework\TestCase;

class CollectionOfIntegersTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testIntegerCollectionAcceptsOnlyIntegers()
    {
        $collection = new CollectionOfIntegers([1, 0, 2]);
        $this->expectException(\TypeError::class);
        $collection[2] = 'Hello world!';
    }
}
