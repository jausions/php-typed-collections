<?php

namespace Abacus11\Collections;

class Integers extends ArrayOf
{
    /**
     * Collection of integers
     *
     * Each element of the collection is an integer
     *
     * @param integer[] $elements
     *
     * @throws \AssertionError
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct(array $elements = [])
    {
        $this->setElementType('integer');
        parent::__construct($elements);
    }
}