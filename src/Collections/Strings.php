<?php

namespace Abacus11\Collections;

class Strings extends ArrayCollectionOf
{
    /**
     * Collection of strings
     *
     * Each element of the collection is a string
     *
     * @param string[] $elements
     *
     * @throws \AssertionError
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct(array $elements = [])
    {
        parent::__construct('string', $elements);
    }
}