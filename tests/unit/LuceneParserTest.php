<?php

use DigitLab\SearchParser\Lexers\Tokens\Token;
use DigitLab\SearchParser\Lexers\Tokens\TokenType;
use DigitLab\SearchParser\Parsers\LuceneParser;

class LuceneParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \DigitLab\SearchParser\Parsers\LuceneParser
     */
    protected $parser;

    public function setUp()
    {
        $this->parser = new LuceneParser();
    }

    protected function tearDown()
    {
        $this->parser = null;
    }

    public function testItCanParseTokens()
    {
        $tokens = [
            new Token('something', TokenType::IDENTIFIER),
            new Token('some:thing', TokenType::FILTER),
            new Token('OR', TokenType::BOOLEAN_OPERATOR),
            new Token('"some phrase"', TokenType::PHRASE),
            new Token('(', TokenType::SYMBOL),
            new Token('-', TokenType::NEGATE),
            new Token('something', TokenType::IDENTIFIER),
            new Token(')', TokenType::SYMBOL),
        ];

        list($expression, $filters) = $this->parser->parse($tokens);

        $this->assertCount(4, $expression);
        $this->assertArrayHasKey('some', $filters);
    }
}
