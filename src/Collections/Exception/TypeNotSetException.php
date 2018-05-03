<?php

namespace Abacus11\Collections\Exception;

if (class_exists('AssertionError')) {
    class TypeNotSetException extends \AssertionError {}
} else {
    class TypeNotSetException extends \LogicException {}
}
