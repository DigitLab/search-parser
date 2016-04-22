<?php

namespace DigitLab\SearchParser\Lexers\Tokens;

final class TokenType
{
    const WHITESPACE = 1;
    const BOOLEAN_OPERATOR = 2;
    const SYMBOL = 3;
    const NEGATE = 4;
    const PHRASE = 5;
    const IDENTIFIER = 6;
    const FILTER = 7;
}