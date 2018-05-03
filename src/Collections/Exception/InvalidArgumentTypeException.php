<?php

namespace Abacus11\Collections\Exception;

if (class_exists('TypeError')) {
    class InvalidArgumentTypeException extends \TypeError {}
} else {
    class InvalidArgumentTypeException extends \InvalidArgumentException {}
}
