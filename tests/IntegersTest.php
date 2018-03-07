<?php

use Abacus11\Collections\Integers;
use PHPUnit\Framework\TestCase;

class IntegersTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testIntegerCollectionAcceptsOnlyIntegers()
    {
        $collection = new Integers([1, 0, 2]);
        $this->expectException(\TypeError::class);
        $collection[2] = 'Hello world!';
    }
}
