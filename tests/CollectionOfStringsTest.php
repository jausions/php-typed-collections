<?php

use Abacus11\Collections\CollectionOfStrings;
use PHPUnit\Framework\TestCase;

class CollectionOfStringsTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testStringCollectionAcceptsOnlyStrings()
    {
        $collection = new CollectionOfStrings(['abc', '']);
        $this->expectException(\TypeError::class);
        $collection[] = true;
    }
}
