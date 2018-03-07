<?php

use Abacus11\Collections\Callables;
use PHPUnit\Framework\TestCase;

class CallablesTest extends TestCase
{
    /**
     * @covers \Abacus11\Collections\Callables::__construct()
     */
    public function testCallableCollectionAcceptsOnlyCallables()
    {
        $collection = new Callables([function(){}, [$this, __FUNCTION__], 'ucfirst']);
        $this->expectException(\TypeError::class);
        $collection['abc'] = 3.1415;
    }
}
