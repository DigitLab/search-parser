<?php

namespace DigitLab\SearchParser\Parsers;

interface Parser
{
    /**
     * Parse the tokens into an expression.
     *
     * @param array $tokens
     *
     * @return \DigitLab\SearchParser\Parsers\Nodes\ExpressionNode
     */
    public function parse(array $tokens);
}
