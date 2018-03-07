<?php

use Abacus11\Collections\JSONs;
use PHPUnit\Framework\TestCase;

class JSONsTest extends TestCase
{
    /**
     * @covers \Abacus11\Collections\JSONs::__construct()
     */
    public function testJSONCollectionAcceptsOnlyJSON()
    {
        $collection = new JSONs(['null', '{"key":"value"}']);
        $this->expectException(\TypeError::class);
        $collection['other'] = function() {};
    }
}
