<?php

use Abacus11\Collections\CollectionOfJSONs;
use PHPUnit\Framework\TestCase;

class CollectionOfJSONsTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testJSONCollectionAcceptsOnlyJSON()
    {
        $collection = new CollectionOfJSONs(['null', '{"key":"value"}']);
        $this->expectException(\TypeError::class);
        $collection['other'] = function() {};
    }
}
