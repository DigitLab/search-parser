<?php

namespace DigitLab\SearchParser\Parsers\Nodes;

class BooleanOperatorNode extends Node
{
    /**
     * @var bool
     */
    protected $and;

    /**
     * BooleanOperatorNode constructor.
     *
     * @param bool $and
     */
    public function __construct($and = true)
    {
        $this->and = $and;
    }

    /**
     * Determines if the boolean operator is an and operator.
     *
     * @return bool
     */
    public function isAnd()
    {
        return $this->and;
    }
}