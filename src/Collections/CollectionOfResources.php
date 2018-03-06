<?php

namespace Abacus11\Collections;

class CollectionOfResources extends TypedArrayCollection
{
    /**
     * Collection of resources
     *
     * Each element of the collection is a resource
     *
     * @param resource[] $elements
     *
     * @throws \AssertionError
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct(array $elements = [])
    {
        $this->setElementType('resource');
        parent::__construct($elements);
    }
}