<?php

use DigitLab\SearchParser\Lexers\LuceneLexer;
use DigitLab\SearchParser\Lexers\Tokens\TokenType;

class LuceneLexerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \DigitLab\SearchParser\Lexers\LuceneLexer
     */
    protected $lexer;

    public function setUp()
    {
        $this->lexer = new LuceneLexer();
    }

    protected function tearDown()
    {
        $this->lexer = null;
    }

    public function testItCanParse()
    {
        $tokens = $this->lexer->tokenize('some:thing ("some phrase" OR blah) blah -blah');

        $this->assertCount(9, $tokens);
    }

    public function testItCanParseFilters()
    {
        $tokens = $this->lexer->tokenize('some:thing');

        $this->assertCount(1, $tokens);
        $this->assertEquals(TokenType::FILTER, $tokens[0]->getType());
        $this->assertEquals('some:thing', $tokens[0]->getValue());
    }

    public function testItCanParseSymbols()
    {
        $tokens = $this->lexer->tokenize('()');

        $this->assertCount(2, $tokens);
        $this->assertEquals(TokenType::SYMBOL, $tokens[0]->getType());
        $this->assertEquals('(', $tokens[0]->getValue());
        $this->assertEquals(TokenType::SYMBOL, $tokens[1]->getType());
        $this->assertEquals(')', $tokens[1]->getValue());
    }

    public function testItCanParsePhrases()
    {
        $tokens = $this->lexer->tokenize('"some thing"');

        $this->assertCount(1, $tokens);
        $this->assertEquals(TokenType::PHRASE, $tokens[0]->getType());
        $this->assertEquals('"some thing"', $tokens[0]->getValue());
    }

    public function testItCanParseIdetifiers()
    {
        $tokens = $this->lexer->tokenize('some');

        $this->assertCount(1, $tokens);
        $this->assertEquals(TokenType::IDENTIFIER, $tokens[0]->getType());
        $this->assertEquals('some', $tokens[0]->getValue());
    }

    public function testItCanParseNegativeIdetifiers()
    {
        $tokens = $this->lexer->tokenize('-some');

        $this->assertCount(2, $tokens);
        $this->assertEquals(TokenType::NEGATE, $tokens[0]->getType());
        $this->assertEquals(TokenType::IDENTIFIER, $tokens[1]->getType());
        $this->assertEquals('some', $tokens[1]->getValue());
    }
}
