<?php

use Abacus11\Collections\Arrays;
use PHPUnit\Framework\TestCase;

class ArraysTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testArrayCollectionAcceptsOnlyArrays()
    {
        $collection = new Arrays([[], [1, 2, 3], ['a', 'b', 'c']]);
        $this->expectException(\TypeError::class);
        $collection[] = false;
    }
}
