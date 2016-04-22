<?php

namespace DigitLab\SearchParser\Parsers;

use DigitLab\SearchParser\Lexers\Tokens\Token;
use DigitLab\SearchParser\Lexers\Tokens\TokenStream;
use DigitLab\SearchParser\Lexers\Tokens\TokenType;
use DigitLab\SearchParser\Parsers\Nodes\BooleanOperatorNode;
use DigitLab\SearchParser\Parsers\Nodes\ExpressionNode;
use DigitLab\SearchParser\Parsers\Nodes\IdentifierNode;
use DigitLab\SearchParser\Parsers\Nodes\PhraseNode;

class LuceneParser implements Parser
{
    /**
     * @param array $tokens
     *
     * @return \DigitLab\SearchParser\Parsers\Nodes\ExpressionNode
     */
    public function parse(array $tokens)
    {
        list($tokens, $filters) = $this->filterTokens($tokens);
        $tokenStream = new TokenStream(array_values($tokens));

        $expression = new ExpressionNode();

        while (!$tokenStream->isEmpty()) {
            $expression = $this->parseNextToken($tokenStream, $expression);
        }

        return [$expression, $filters];
    }

    /**
     * Filter out filter tokens.
     *
     * @param array $tokens
     *
     * @return array
     */
    protected function filterTokens(array $tokens)
    {
        $filters = [];
        $filteredTokens = array_filter($tokens, function (Token $token) use (&$filters) {
            if ($token->getType() == TokenType::FILTER) {
                $this->parseFilterToken($token, $filters);

                return false;
            }

            return $token->getType() != TokenType::WHITESPACE;
        });

        return [$filteredTokens, $filters];
    }

    protected function parseNextToken(TokenStream $tokenStream, ExpressionNode $expression)
    {
        switch ($tokenStream->currentToken()->getType()) {
            case TokenType::SYMBOL:
                $expression = $this->parseSymbolToken($tokenStream, $expression);
                break;

            case TokenType::BOOLEAN_OPERATOR:
                $this->parseBooleanOperatorToken($tokenStream, $expression);
                break;

            case TokenType::PHRASE:
                $this->parsePhraseToken($tokenStream, $expression);
                break;

            default:
                $this->parseIdentifierToken($tokenStream, $expression);
        }

        return $expression;
    }

    /**
     * Parse the boolean operator.
     *
     * @param \DigitLab\SearchParser\Lexers\Tokens\TokenStream    $tokenStream
     * @param \DigitLab\SearchParser\Parsers\Nodes\ExpressionNode $expression
     */
    protected function parseBooleanOperatorToken(TokenStream $tokenStream, ExpressionNode $expression)
    {
        $token = $tokenStream->consumeToken();

        $expression[] = new BooleanOperatorNode($token->getValue() == 'AND');
    }

    /**
     * Parse the filter token and add it to the filters.
     *
     * @param \DigitLab\SearchParser\Lexers\Tokens\Token $token
     * @param array                                      $filters
     */
    protected function parseFilterToken(Token $token, array &$filters)
    {
        $segments = explode(':', $token->getValue());

        $filters[$segments[0]] = $segments[1];
    }

    /**
     * Parse the phrase token.
     *
     * @param \DigitLab\SearchParser\Lexers\Tokens\TokenStream    $tokenStream
     * @param \DigitLab\SearchParser\Parsers\Nodes\ExpressionNode $expression
     */
    protected function parsePhraseToken(TokenStream $tokenStream, ExpressionNode $expression)
    {
        $token = $tokenStream->consumeToken();
        $value = $token->getValue();

        $value = trim($value, '"');
        $value = trim($value, "'");

        $expression[] = new PhraseNode($value);
    }

    /**
     * Parse the identifier token.
     *
     * @param \DigitLab\SearchParser\Lexers\Tokens\TokenStream    $tokenStream
     * @param \DigitLab\SearchParser\Parsers\Nodes\ExpressionNode $expression
     */
    protected function parseIdentifierToken(TokenStream $tokenStream, ExpressionNode $expression)
    {
        $token = $tokenStream->consumeToken();
        $negate = false;

        if ($token->getType() === TokenType::NEGATE) {
            $negate = true;
            $token = $tokenStream->consumeToken();
        }

        $expression[] = new IdentifierNode($token->getValue(), $negate);
    }

    /**
     * Parse the symbol token.
     *
     * @param \DigitLab\SearchParser\Lexers\Tokens\TokenStream    $tokenStream
     * @param \DigitLab\SearchParser\Parsers\Nodes\ExpressionNode $expression
     *
     * @return \DigitLab\SearchParser\Parsers\Nodes\ExpressionNode
     */
    protected function parseSymbolToken(TokenStream $tokenStream, ExpressionNode $expression)
    {
        $token = $tokenStream->consumeToken();

        if ($token->getValue() == '(') {
            return $expression->createChildExpression();
        }

        return $expression->getParent();
    }
}
