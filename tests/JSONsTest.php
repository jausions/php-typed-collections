<?php

use Abacus11\Collections\JSONs;
use PHPUnit\Framework\TestCase;

class JSONsTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testJSONCollectionAcceptsOnlyJSON()
    {
        $collection = new JSONs(['null', '{"key":"value"}']);
        $this->expectException(\TypeError::class);
        $collection['other'] = function() {};
    }
}
