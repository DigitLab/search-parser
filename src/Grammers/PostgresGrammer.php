<?php

namespace DigitLab\SearchParser\Grammers;

use DigitLab\SearchParser\Parsers\Nodes\BooleanOperatorNode;
use DigitLab\SearchParser\Parsers\Nodes\ExpressionNode;
use DigitLab\SearchParser\Parsers\Nodes\IdentifierNode;
use DigitLab\SearchParser\Parsers\Nodes\PhraseNode;

class PostgresGrammer implements Grammer
{
    /**
     * Generate a search string from the expression.
     *
     * @param \DigitLab\SearchParser\Parsers\Nodes\ExpressionNode $expression
     * @return string
     */
    public function generate(ExpressionNode $expression)
    {
        return trim($this->process($expression));
    }

    protected function process(ExpressionNode $expression)
    {
        $query = '';
        $needsBoolean = false;
        
        foreach ($expression as $node) {
            if ($node instanceof BooleanOperatorNode) {
                $operator = $node->isAnd() ? '&' : '|';
                $query .= "$operator";

                $needsBoolean = false;
                continue;
            }

            if ($needsBoolean) {
                $query .= '&';
            }

            switch (get_class($node)) {
                case ExpressionNode::class:
                    $query .= '(' . $this->process($node) . ')';
                    break;

                case IdentifierNode::class:
                    $not = $node->isNegative() ? '!' : '';
                    $query .= "$not{$node->getValue()}:*";
                    break;

                case PhraseNode::class:
                    $query .= "'{$node->getValue()}'";
                    break;
            }

            $needsBoolean = true;
        }

        return $query;
    }
}