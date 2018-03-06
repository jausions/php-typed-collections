<?php

use Abacus11\Collections\CollectionOfArrays;
use PHPUnit\Framework\TestCase;

class CollectionOfArraysTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testArrayCollectionAcceptsOnlyArrays()
    {
        $collection = new CollectionOfArrays([[], [1, 2, 3], ['a', 'b', 'c']]);
        $this->expectException(\TypeError::class);
        $collection[] = false;
    }
}
