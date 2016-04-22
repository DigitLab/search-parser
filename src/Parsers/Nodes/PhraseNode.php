<?php

namespace DigitLab\SearchParser\Parsers\Nodes;

class PhraseNode extends Node
{
    /**
     * @var string
     */
    protected $value;

    /**
     * PhraseNode constructor.
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Get the node value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}