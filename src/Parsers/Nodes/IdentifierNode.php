<?php

namespace DigitLab\SearchParser\Parsers\Nodes;

class IdentifierNode extends PhraseNode
{
    /**
     * @var bool
     */
    protected $negate;

    /**
     * PhraseNode constructor.
     * @param string $value
     * @param bool $negate
     */
    public function __construct($value, $negate = false)
    {
        parent::__construct($value);

        $this->negate = $negate;
    }

    /**
     * Determine if the phrase is negative.
     *
     * @return bool
     */
    public function isNegative()
    {
        return $this->negate;
    }
}