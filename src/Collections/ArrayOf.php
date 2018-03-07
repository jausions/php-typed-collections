<?php

namespace Abacus11\Collections;

use Doctrine\Common\Collections\ArrayCollection;

class ArrayOf extends ArrayCollection implements TypedCollection
{
    use TypedCollectionTrait;

    /**
     * Initialize the array-like collection
     *
     * The first element will determine the type for the collection.
     *
     * @param array $elements
     *
     * @throws \AssertionError
     * @throws \Exception
     * @throws \TypeError
     *
     * @see ArrayOf::setElementType()
     * @see ArrayOf::setElementTypeLike()
     */
    public function __construct(array $elements = [])
    {
        if (!empty($elements)) {
            if (!isset($this->element_type_checker)) {
                $this->setElementTypeLike(reset($elements));
            }
            foreach ($elements as $element) {
                if (!$this->isElementType($element)) {
                    throw new \TypeError('The values in the array are not of the same type');
                }
            }
        }
        parent::__construct($elements);
    }
}