<?php

namespace DigitLab\SearchParser\Lexers;

use DigitLab\SearchParser\Lexers\Tokens\TokenType;

class LuceneLexer extends Lexer
{
    /**
     * The token definitions for the lexer.
     *
     * @var array
     */
    protected $tokenDefinitions = [
        TokenType::FILTER               => '/[a-zA-Z0-9]+:[a-zA-Z0-9]*/',
        TokenType::SYMBOL               => '/\(|\)/',
        TokenType::BOOLEAN_OPERATOR     => '/AND|OR/',
        TokenType::PHRASE               => '/".*"|\'.*\'/',
        TokenType::NEGATE               => '/-/',
        TokenType::IDENTIFIER           => '/[!|>|>=|<|<=]?[a-zA-Z0-9]+/'
    ];
}