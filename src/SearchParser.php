<?php

namespace DigitLab\SearchParser;

use DigitLab\SearchParser\Grammers\Grammer;
use DigitLab\SearchParser\Lexers\Lexer;
use DigitLab\SearchParser\Parsers\Parser;
use Illuminate\Support\Str;

class SearchParser
{
    /**
     * @var \DigitLab\SearchParser\Lexers\Lexer
     */
    protected $lexer;

    /**
     * @var \DigitLab\SearchParser\Parsers\Parser
     */
    protected $parser;

    /**
     * @var \DigitLab\SearchParser\Grammers\Grammer
     */
    protected $grammer;

    /**
     * The name of the query in the result array.
     *
     * @var string
     */
    protected $queryName = 'search';

    /**
     * The filters that should be returned without handlers.
     *
     * @var array
     */
    protected $passthruFilters = [];

    /**
     * Create a new SearchParser.
     *
     * @param \DigitLab\SearchParser\Lexers\Lexer $lexer
     * @param \DigitLab\SearchParser\Parsers\Parser $parser
     * @param \DigitLab\SearchParser\Grammers\Grammer $grammer
     */
    public function __construct(Lexer $lexer, Parser $parser, Grammer $grammer)
    {
        $this->grammer = $grammer;
        $this->lexer = $lexer;
        $this->parser = $parser;
    }

    /**
     * Parse the query.
     *
     * @param string $query
     * @return array
     */
    public function parse($query)
    {
        if (!$query) {
            return [];
        }

        $tokens = $this->lexer->tokenize($query);

        list($expression, $filters) = $this->parser->parse($tokens);

        $search = $this->grammer->generate($expression);

        $filters = $this->processFilters($filters);

        if (!$search) {
            return $filters;
        }

        return array_merge([$this->queryName => $search], $filters);
    }

    /**
     * Process the filters.
     *
     * @param array $filters
     * @return array
     */
    protected function processFilters($filters)
    {
        $results = [];

        foreach ($filters as $key => $value) {
            if (in_array($key, $this->passthruFilters)) {
                $results[$key] = $value;
            }

            if ($this->hasFilterMutator($key)) {
                $results = array_merge($results, $this->mutateFilter($key, $value));
            }
        }

        return $results;
    }

    /**
     * Determine if a get mutator exists for a filter.
     *
     * @param string $key
     * @return bool
     */
    protected function hasFilterMutator($key)
    {
        return method_exists($this, 'handle'.Str::studly($key));
    }

    /**
     * Get the value of a filter using its mutator.
     *
     * @param string $key
     * @param mixed $value
     * @return array
     */
    protected function mutateFilter($key, $value)
    {
        return $this->{'handle'.Str::studly($key)}($value);
    }
}