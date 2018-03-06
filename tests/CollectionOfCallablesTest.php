<?php

use Abacus11\Collections\CollectionOfCallables;
use PHPUnit\Framework\TestCase;

class CollectionOfCallablesTest extends TestCase
{
    /**
     * @throws AssertionError
     * @throws Exception
     * @throws TypeError
     */
    public function testCallableCollectionAcceptsOnlyCallables()
    {
        $collection = new CollectionOfCallables([function(){}, [$this, __FUNCTION__], 'ucfirst']);
        $this->expectException(\TypeError::class);
        $collection['abc'] = 3.1415;
    }
}
