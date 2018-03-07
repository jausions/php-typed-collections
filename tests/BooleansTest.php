<?php

use Abacus11\Collections\Booleans;
use PHPUnit\Framework\TestCase;

class BooleansTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testBooleanCollectionAcceptsOnlyBooleans()
    {
        $collection = new Booleans([false, true, false]);
        $this->expectException(\TypeError::class);
        $collection[1] = 'abc';
    }
}
