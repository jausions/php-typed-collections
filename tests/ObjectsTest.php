<?php

use Abacus11\Collections\Objects;
use PHPUnit\Framework\TestCase;

class CollectionOfObjectsTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testObjectCollectionAcceptsOnlyObjects()
    {
        $collection = new Objects([new stdClass(), new class {}, function() {}, $this]);
        $this->expectException(\TypeError::class);
        $collection[] = 'text';
    }
}
