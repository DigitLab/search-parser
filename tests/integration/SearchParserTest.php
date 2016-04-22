<?php


use DigitLab\SearchParser\Grammers\PostgresGrammer;
use DigitLab\SearchParser\Lexers\LuceneLexer;
use DigitLab\SearchParser\Parsers\LuceneParser;
use DigitLab\SearchParser\SearchParser;

class SearchParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \DigitLab\SearchParser\SearchParser
     */
    protected $searchParser;

    public function setUp()
    {
        $this->searchParser = new SearchParser(
            new LuceneLexer(),
            new LuceneParser(),
            new PostgresGrammer()
        );
    }

    protected function tearDown()
    {
        $this->searchParser = null;
    }

    public function testItCanParseLucene()
    {
        $filters = $this->searchParser->parse('("some thing" OR some1) OR some2 some3 -some4');

        $this->assertArrayHasKey('search', $filters);
        $this->assertEquals('(\'some thing\'|some1:*)|some2:*&some3:*&!some4:*', $filters['search']);
    }

    public function testItCanHandleAnEmptyString()
    {
        $filters = $this->searchParser->parse('');

        $this->assertCount(0, $filters);
    }

    public function testItDiscardsUnknownFilters()
    {
        $filters = $this->searchParser->parse('some:thing other:thing');

        $this->assertCount(0, $filters);
    }

    public function testItCanParseFilters()
    {
        $customParser = new CustomSearchParser(new LuceneLexer(), new LuceneParser(), new PostgresGrammer());
        $filters = $customParser->parse('state:pending');

        $this->assertArrayHasKey('some', $filters);
        $this->assertEquals('pending', $filters['some']);
    }

    public function testItCanAllowsPassThroughFilters()
    {
        $customParser = new CustomSearchParser(new LuceneLexer(), new LuceneParser(), new PostgresGrammer());
        $filters = $customParser->parse('other:thing');

        $this->assertArrayHasKey('other', $filters);
        $this->assertEquals('thing', $filters['other']);
    }
}

class CustomSearchParser extends SearchParser
{
    protected $passthruFilters = ['other'];

    protected function handleState($state)
    {
        return ['some' => $state];
    }
}