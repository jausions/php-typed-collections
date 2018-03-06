<?php

namespace Abacus11\Collections;

/**
 * Trait to implement constraints on elements of a collection
 *
 * @author Philippe Jausions <Philippe.Jausions@11abacus.com>
 * @see \Doctrine\Common\Collections\Collection
 */
trait TypedCollectionTrait
{
    /**
     * @var \Closure
     */
    private $element_type_checker;

    /**
     * Returns if the argument is of the collection's type
     *
     * @param mixed $element Value to check
     *
     * @return boolean
     *
     * @throws \AssertionError when the criteria is not set
     */
    public function isElementType($element)
    {
        // If assert is turned off in your php.ini you may receive
        // the error: "Function name must be a string" if the collection
        // type wasn't set before trying to add elements.
        assert(isset($this->element_type_checker), 'The criteria for the collection is not defined');
        return ($this->element_type_checker)($element);
    }

    /**
     * Adds an element at the end of the collection.
     *
     * @param mixed $element The element to add.
     *
     * @return bool Always TRUE.
     *
     * @throws \TypeError when the value doesnt match the criteria
     * @throws \AssertionError when the criteria is not set
     */
    public function add($element)
    {
        if (!$this->isElementType($element)) {
            throw new \TypeError('The value does not comply with the criteria for the collection.');
        }
        return parent::add($element);
    }

    /**
     * Sets an element in the collection at the specified key/index.
     *
     * @param string|int $key The key/index of the element to set.
     * @param mixed $value The element to set.
     *
     * @return void
     *
     * @throws \TypeError when the value doesnt match the criteria
     * @throws \AssertionError when the criteria is not set
     */
    public function set($key, $value)
    {
        if (!$this->isElementType($value)) {
            throw new \TypeError('The value does not comply with the criteria for the collection.');
        }
        parent::set($key, $value);
    }

    /**
     * Defines the criteria for adding elements to the collection
     *
     * If a closure is passed, it needs to expect one argument and must
     * return TRUE or FALSE, for valid and invalid values respectively.
     *
     * If a string is passed, it can either be a fully qualified class name
     * or one of the following:
     * <ul>
     *  <li>array</li>
     *  <li>boolean</li>
     *  <li>callable</li>
     *  <li>double</li>
     *  <li>integer</li>
     *  <li>number</li>
     *  <li>json</li>
     *  <li>object</li>
     *  <li>resource</li>
     *  <li>string</li>
     * </ul>
     *
     * @param string|\Closure $criteria
     *
     * @return $this
     *
     * @throws \TypeError
     * @throws \Exception
     *
     * @see TypedCollection::setElementTypeLike()
     */
    public function setElementType($criteria)
    {
        if (isset($this->element_type_checker)) {
            throw new \Exception('The criteria for the collection cannot be changed');
        }
        if (!$this->isEmpty()) {
            throw new \Exception('The criteria cannot be set for a non-empty collection');
        }

        if (is_string($criteria)) {
            // We are a bit more tolerant with aliases
            switch (strtolower($criteria)) {
                case 'string':
                case 'text':
                    $this->element_type_checker = 'is_string';
                    break;
                case 'double':
                case 'float':
                    $this->element_type_checker = 'is_float';
                    break;
                case 'array':
                    $this->element_type_checker = 'is_array';
                    break;
                case 'resource':
                case 'resource (closed)':
                    $this->element_type_checker = 'is_resource';
                    break;
                case 'integer':
                case 'int':
                    $this->element_type_checker = 'is_int';
                    break;
                case 'number':
                    $this->element_type_checker = 'is_numeric';
                    break;
                case 'object':
                    $this->element_type_checker = 'is_object';
                    break;
                case 'callable':
                case 'closure':
                case 'callback':
                case 'function':
                    $this->element_type_checker = 'is_callable';
                    break;
                case 'boolean':
                case 'bool':
                    $this->element_type_checker = 'is_bool';
                    break;
                case 'json':
                    $this->element_type_checker = function($element) {
                        if (!is_string($element)) {
                            return false;
                        }
                        if (strcasecmp($element, 'null') == 0) {
                            return true;
                        }
                        return (json_decode($element) !== null);
                    };
                    break;
                default:
                    // Class name
                    $this->element_type_checker = function($element) use ($criteria) {
                        return ($element instanceof $criteria);
                    };
            }
        } elseif (is_callable($criteria)) {
            $this->element_type_checker = $criteria;
        } else {
            throw new \TypeError('Invalid criteria to check elements of the collection.');
        }
        return $this;
    }

    /**
     * Defines the criteria for adding elements to the collection
     *
     * The built-in type or class of the argument will be used as criteria.
     *
     * @param mixed $sample
     *
     * @return $this
     *
     * @throws \TypeError
     * @throws \InvalidArgumentException
     * @throws \Exception
     *
     * @see TypedCollection::setElementType()
     */
    public function setElementTypeLike($sample)
    {
        if ($sample === null) {
            throw new \InvalidArgumentException('Sample element cannot be NULL');
        } elseif (is_object($sample)) {
            return $this->setElementType(get_class($sample));
        }
        return $this->setElementType(gettype($sample));
    }
}