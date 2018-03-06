<?php

use Abacus11\Collections\CollectionOfNumbers;
use PHPUnit\Framework\TestCase;

class CollectionOfNumbersTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testNumberCollectionAcceptsOnlyNumbers()
    {
        $collection = new CollectionOfNumbers(['123', -876543, 0, 9.876, '-4.5']);
        $this->expectException(\TypeError::class);
        $collection[] = new stdClass();
    }
}
