<?php

namespace Abacus11\Collections;

class Objects extends ArrayOf
{
    /**
     * Collection of objects
     *
     * Each element of the collection is an object
     *
     * @param object[] $elements
     *
     * @throws \AssertionError
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct(array $elements = [])
    {
        $this->setElementType('object');
        parent::__construct($elements);
    }
}