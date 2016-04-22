<?php

namespace DigitLab\SearchParser\Lexers\Tokens;

class TokenStream implements \Countable
{
    /**
     * The tokens in the stream.
     *
     * @var array
     */
    protected $tokens;

    /**
     * TokenStream constructor.
     *
     * @param array $tokens
     */
    public function __construct(array $tokens = [])
    {
        $this->tokens = $tokens;
    }

    /**
     * Add a token to the token stream.
     *
     * @param \DigitLab\SearchParser\Lexers\Tokens\Token $token
     */
    public function addToken(Token $token)
    {
        $this->tokens[] = $token;
    }

    /**
     * Get the current token.
     *
     * @return \DigitLab\SearchParser\Lexers\Tokens\Token|null
     */
    public function currentToken()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->tokens[0];
    }

    /**
     * Determines if the token straem is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->tokens) === 0;
    }

    /**
     * Get the number of tokens in the stream.
     *
     * @return int
     */
    public function count()
    {
        return count($this->tokens);
    }

    /**
     * Get the token ahead of the current token in the stream.
     *
     * @param int $position
     * @return \DigitLab\SearchParser\Lexers\Tokens\Token|null
     */
    public function lookAhead($position = 1)
    {
        if ($position >= count($this->tokens)) {
            return null;
        }

        return $this->tokens[$position];
    }

    /**
     * @return \DigitLab\SearchParser\Lexers\Tokens\Token
     */
    public function consumeToken()
    {
        return array_shift($this->tokens);
    }
}