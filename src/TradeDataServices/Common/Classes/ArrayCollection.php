<?php

namespace TradeDataServices\Common\Classes;

use ArrayAccess;
use ArrayIterator;
use Closure;
use IteratorAggregate;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class ArrayCollection implements IteratorAggregate, ArrayAccess
{

    /**
     * An array containing the entries of this collection.
     *
     * @var array
     */
    private $elements;


    /**
     * Initializes a new ArrayCollection.
     *
     * @param array $elements
     */
    public function __construct(array $elements = array ())
    {
        $this->elements = $elements;
    }


    /**
     * Creates a new instance from the specified elements.
     *
     * This method is provided for derived classes to specify how a new
     * instance should be created when constructor semantics have changed.
     *
     * @param array $elements Elements.
     *
     * @return static
     */
    protected function createFrom(array $elements)
    {
        return new static($elements);
    }


    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->elements;
    }


    /**
     * {@inheritDoc}
     */
    public function first()
    {
        return reset($this->elements);
    }


    /**
     * {@inheritDoc}
     */
    public function last()
    {
        return end($this->elements);
    }


    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return key($this->elements);
    }


    /**
     * {@inheritDoc}
     */
    public function next()
    {
        return next($this->elements);
    }


    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return current($this->elements);
    }


    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        if (!isset($this->elements[$key]) && !array_key_exists($key, $this->elements)) {
            return null;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        return $removed;
    }


    /**
     * {@inheritDoc}
     */
    public function removeElement($element)
    {
        $key = array_search($element, $this->elements, true);

        if ($key === false) {
            return false;
        }

        unset($this->elements[$key]);

        return true;
    }


    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }


    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }


    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            return $this->add($value);
        }

        $this->set($offset, $value);
    }


    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }


    /**
     * {@inheritDoc}
     */
    public function containsKey($key)
    {
        return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
    }


    /**
     * {@inheritDoc}
     */
    public function contains($element)
    {
        return in_array($element, $this->elements, true);
    }


    /**
     * {@inheritDoc}
     */
    public function exists(Closure $function)
    {
        foreach ($this->elements as $key => $element) {
            if ($function($key, $element)) {
                return true;
            }
        }

        return false;
    }


    /**
     * {@inheritDoc}
     */
    public function indexOf($element)
    {
        return array_search($element, $this->elements, true);
    }


    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return isset($this->elements[$key]) ? $this->elements[$key] : null;
    }


    /**
     * {@inheritDoc}
     */
    public function getKeys()
    {
        return array_keys($this->elements);
    }


    /**
     * {@inheritDoc}
     */
    public function getValues()
    {
        return array_values($this->elements);
    }


    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->elements);
    }


    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $this->elements[$key] = $value;
    }


    /**
     * {@inheritDoc}
     */
    public function add($element)
    {
        $this->elements[] = $element;

        return true;
    }


    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
        return empty($this->elements);
    }


    /**
     * Required by interface IteratorAggregate.
     *
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->elements);
    }


    /**
     * {@inheritDoc}
     */
    public function map(Closure $func)
    {
        return $this->createFrom(array_map($func, $this->elements));
    }


    /**
     * {@inheritDoc}
     */
    public function filter(Closure $function)
    {
        return $this->createFrom(array_filter($this->elements, $function));
    }


    /**
     * {@inheritDoc}
     */
    public function forAll(Closure $function)
    {
        foreach ($this->elements as $key => $element) {
            if (!$function($key, $element)) {
                return false;
            }
        }

        return true;
    }


    /**
     * {@inheritDoc}
     */
    public function partition(Closure $function)
    {
        $matches   = $noMatches = array ();

        foreach ($this->elements as $key => $element) {
            if ($function($key, $element)) {
                $matches[$key] = $element;
            }
            $noMatches[$key] = $element;
        }

        return array ($this->createFrom($matches), $this->createFrom($noMatches));
    }


    /**
     * Returns a string representation of this object.
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . '@' . spl_object_hash($this);
    }


    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        $this->elements = array ();
    }


    /**
     * {@inheritDoc}
     */
    public function slice($offset, $length = null)
    {
        return array_slice($this->elements, $offset, $length, true);
    }
}
