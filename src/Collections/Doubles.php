<?php

namespace Abacus11\Collections;

class Doubles extends ArrayCollectionOf
{
    /**
     * Collection of doubles
     *
     * Each element of the collection is a double (also known as float in PHP.)
     *
     * @param double[] $elements
     *
     * @throws \AssertionError
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct(array $elements = [])
    {
        parent::__construct('double', $elements);
    }
}