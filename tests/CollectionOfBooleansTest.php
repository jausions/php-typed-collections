<?php

use Abacus11\Collections\CollectionOfBooleans;
use PHPUnit\Framework\TestCase;

class CollectionOfBooleansTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testBooleanCollectionAcceptsOnlyBooleans()
    {
        $collection = new CollectionOfBooleans([false, true, false]);
        $this->expectException(\TypeError::class);
        $collection[1] = 'abc';
    }
}
