<?php

namespace DigitLab\SearchParser\Lexers;

use DigitLab\SearchParser\Lexers\Tokens\Token;

abstract class Lexer
{
    /**
     * The token definitions for the lexer.
     *
     * @var array
     */
    protected $tokenDefinitions = [];

    /**
     * Lex the input string into tokens.
     *
     * @param string $input
     * @return array
     */
    public function tokenize($input)
    {
        $column = 0;
        $length = strlen($input);
        $tokens = [];

        while ($column < $length) {
            $token = $this->findMatchingToken(substr($input, $column));

            if (!$token) {
                // If we didn't find a token, move the column over one
                $column++;
                continue;
            }

            $tokens[] = $token;
            $column += strlen($token->getValue());
        }

        return $tokens;
    }

    /**
     * Find the first matching token in a string.
     *
     * @param $input
     * @return \DigitLab\SearchParser\Lexers\Tokens\Token|null
     */
    protected function findMatchingToken($input)
    {
        $matchingTokens = [];

        foreach ($this->tokenDefinitions as $name => $definition) {
            $token = $this->getTokenFromDefinition($input, $name, $definition);

            if ($token) {
                $matchingTokens[] = $token;
            }
        }

        if (empty($matchingTokens)) {
            return null;
        }

        return $this->getBestToken($matchingTokens);
    }

    /**
     * Get a token or null for a token definition.
     *
     * @param string $input
     * @param string $tokenName
     * @param string $tokenDefinition
     * @return \DigitLab\SearchParser\Lexers\Tokens\Token|null
     */
    protected function getTokenFromDefinition($input, $tokenName, $tokenDefinition)
    {
        $result = preg_match($tokenDefinition, $input, $matches, PREG_OFFSET_CAPTURE);

        if ($result === 0) {
            return null;
        }

        $match = $matches[0];

        // Check the offset
        if ($match[1] !== 0) {
            return null;
        }

        return new Token(trim($match[0]), $tokenName);
    }

    protected function getBestToken(array $tokens)
    {
        $maxItem = null;
        $maxValue = null;
        
        foreach ($tokens as $token) {
            $itemValue = strlen($token->getValue());

            if (!$maxItem || $itemValue > $maxValue) {
                $maxItem = $token;
                $maxValue = $itemValue;
            }
        }
        return $maxItem;
    }
}