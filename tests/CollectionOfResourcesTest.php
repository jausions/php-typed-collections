<?php

use Abacus11\Collections\CollectionOfResources;
use PHPUnit\Framework\TestCase;

class CollectionOfResourcesTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testResourceCollectionAcceptsOnlyResources()
    {
        $collection = new CollectionOfResources([fopen(__FILE__, 'r'), opendir(__DIR__)]);
        $this->expectException(\TypeError::class);
        $collection[] = 123;
    }
}
