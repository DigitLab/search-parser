<?php

namespace DigitLab\SearchParser\Parsers\Nodes;

use Traversable;

class ExpressionNode extends Node implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * Nodes contained by the expression.
     *
     * @var array
     */
    protected $nodes = [];

    /**
     * The parent expression.
     *
     * @var \DigitLab\SearchParser\Parsers\Nodes\ExpressionNode
     */
    protected $parent;

    /**
     * ExpressionNode constructor.
     *
     * @param \DigitLab\SearchParser\Parsers\Nodes\ExpressionNode $parent
     */
    public function __construct(ExpressionNode $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * Create a child expression.
     *
     * @return \DigitLab\SearchParser\Parsers\Nodes\ExpressionNode
     */
    public function createChildExpression()
    {
        $child = new self($this);
        $this->nodes[] = $child;

        return $child;
    }

    /**
     * Get the parent expression.
     *
     * @return \DigitLab\SearchParser\Parsers\Nodes\ExpressionNode
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->nodes);
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->nodes);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->nodes);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->nodes[$key];
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->nodes[] = $value;
        } else {
            $this->nodes[$key] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->nodes[$key]);
    }
}