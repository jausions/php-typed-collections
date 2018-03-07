<?php

use Abacus11\Collections\Strings;
use PHPUnit\Framework\TestCase;

class StringsTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testStringCollectionAcceptsOnlyStrings()
    {
        $collection = new Strings(['abc', '']);
        $this->expectException(\TypeError::class);
        $collection[] = true;
    }
}
