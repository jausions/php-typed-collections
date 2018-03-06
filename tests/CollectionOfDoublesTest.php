<?php

use Abacus11\Collections\CollectionOfDoubles;
use PHPUnit\Framework\TestCase;

class CollectionOfDoublesTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testDoubleCollectionAcceptsOnlyDoubles()
    {
        $collection = new CollectionOfDoubles([1.1, 2.0, -3.45]);
        $this->expectException(\TypeError::class);
        $collection['xyz'] = 3;
    }
}
