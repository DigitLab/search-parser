<?php

namespace DigitLab\SearchParser\Grammers;

use DigitLab\SearchParser\Parsers\Nodes\ExpressionNode;

interface Grammer
{
    /**
     * Generate a search string from the expression.
     *
     * @param \DigitLab\SearchParser\Parsers\Nodes\ExpressionNode $expression
     * @return string
     */
    public function generate(ExpressionNode $expression);
}