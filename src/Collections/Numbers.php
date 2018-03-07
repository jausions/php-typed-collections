<?php

namespace Abacus11\Collections;

class Numbers extends ArrayOf
{
    /**
     * Collection of numbers
     *
     * Each element of the collection is a number
     *
     * @param number[] $elements
     *
     * @throws \AssertionError
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct(array $elements = [])
    {
        parent::__construct('number', $elements);
    }
}