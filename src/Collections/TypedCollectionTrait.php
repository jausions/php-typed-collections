<?php

namespace Abacus11\Collections;

use Abacus11\Collections\Exception\CannotChangeTypeException;
use Abacus11\Collections\Exception\InvalidSampleException;
use Abacus11\Collections\Exception\InvalidTypeDefinitionException;
use Abacus11\Collections\Exception\TypeNotSetException;

/**
 * Trait to implement the TypedCollection interface
 */
trait TypedCollectionTrait
{
    /**
     * @var \Closure
     */
    private $element_type_checker = null;

    /**
     * Returns whether the argument is of the collection's type
     *
     * @param mixed $element Value to check
     *
     * @return boolean
     *
     * @throws TypeNotSetException
     */
    public function isElementType($element)
    {
        if (!$this->isElementTypeSet()) {
            throw new TypeNotSetException("The collection's element type is not set.");
        }

        return call_user_func($this->element_type_checker, $element);
    }

    /**
     * Returns whether the collection has a type
     *
     * @return boolean
     */
    public function isElementTypeSet()
    {
        return isset($this->element_type_checker);
    }

    /**
     * @inheritDoc
     *
     * @throws CannotChangeTypeException
     * @throws InvalidTypeDefinitionException
     */
    public function setElementType($criterion)
    {
        if ($this->isElementTypeSet()) {
            throw new CannotChangeTypeException('The criterion for the collection cannot be changed');
        }

        if (is_string($criterion)) {
            // We are a bit more tolerant with aliases
            switch (strtolower($criterion)) {
                case self::OF_ANYTHING:
                case 'anything':
                case '*':
                    $this->element_type_checker = static function($element) {
                        return true;
                    };
                    break;
                case self::OF_ARRAYS:
                    $this->element_type_checker = 'is_array';
                    break;
                case 'bool':
                case self::OF_BOOLEANS:
                    $this->element_type_checker = 'is_bool';
                    break;
                case self::OF_CALLABLES:
                case 'callback':
                case 'closure':
                case 'fn':
                case 'function':
                    $this->element_type_checker = 'is_callable';
                    break;
                case self::OF_CLASSES_AND_INTERFACES:
                    $this->element_type_checker = static function($element) {
                        return is_string($element)
                            && (class_exists($element) || interface_exists($element));
                    };
                    break;
                case self::OF_DOUBLES:
                case 'float':
                    $this->element_type_checker = 'is_float';
                    break;
                case 'int':
                case self::OF_INTEGERS:
                    $this->element_type_checker = 'is_int';
                    break;
                case self::OF_JSON_STRINGS:
                    $this->element_type_checker = static function($element) {
                        if (!is_string($element)) {
                            return false;
                        }
                        if (strcasecmp($element, 'null') === 0) {
                            return true;
                        }
                        $decoded = json_decode($element);
                        return null !== $decoded || json_last_error() === JSON_ERROR_NONE;
                    };
                    break;
                case self::OF_NUMBERS:
                    $this->element_type_checker = 'is_numeric';
                    break;
                case self::OF_OBJECTS:
                    $this->element_type_checker = 'is_object';
                    break;
                case self::OF_PHP_RESOURCES:
                case 'resource (closed)':
                    $this->element_type_checker = 'is_resource';
                    break;
                case self::OF_STRINGS:
                case 'text':
                    $this->element_type_checker = 'is_string';
                    break;
                default:
                    // Class name
                    $this->element_type_checker = static function($element) use ($criterion) {
                        return ($element instanceof $criterion);
                    };
            }
        } elseif (is_callable($criterion)) {
            $this->element_type_checker = $criterion;
        } else {
            throw new InvalidTypeDefinitionException('Invalid criterion to check elements of the collection.');
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
     * @throws InvalidSampleException
     *
     * @see TypedCollection::setElementType()
     */
    public function setElementTypeLike($sample)
    {
        if ($sample === null) {
            throw new InvalidSampleException('Sample element cannot be NULL');
        }
        if (is_object($sample)) {
            return $this->setElementType(get_class($sample));
        }
        return $this->setElementType(gettype($sample));
    }
}
