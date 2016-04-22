<?php

namespace DigitLab\SearchParser\Lexers\Tokens;

class Token
{
    /**
     * The token type.
     *
     * @var int
     */
    protected $type;

    /**
     * The token value.
     *
     * @var string
     */
    protected $value;

    /**
     * Token constructor.
     *
     * @param string $value
     * @param string $type
     */
    public function __construct($value, $type)
    {
        $this->value = $value;
        $this->type = $type;
    }

    /**
     * Get the token type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the token value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
