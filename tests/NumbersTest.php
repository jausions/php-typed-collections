<?php

use Abacus11\Collections\Numbers;
use PHPUnit\Framework\TestCase;

class NumbersTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testNumberCollectionAcceptsOnlyNumbers()
    {
        $collection = new Numbers(['123', -876543, 0, 9.876, '-4.5']);
        $this->expectException(\TypeError::class);
        $collection[] = new stdClass();
    }
}
