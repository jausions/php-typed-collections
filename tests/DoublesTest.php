<?php

use Abacus11\Collections\Doubles;
use PHPUnit\Framework\TestCase;

class DoublesTest extends TestCase
{
    /**
     * @covers \Abacus11\Collections\Doubles::__construct()
     */
    public function testDoubleCollectionAcceptsOnlyDoubles()
    {
        $collection = new Doubles([1.1, 2.0, -3.45]);
        $this->expectException(\TypeError::class);
        $collection['xyz'] = 3;
    }
}
