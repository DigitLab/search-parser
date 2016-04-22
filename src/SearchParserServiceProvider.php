<?php

namespace DigitLab\SearchParser;

use DigitLab\SearchParser\Grammers\Grammer;
use DigitLab\SearchParser\Grammers\PostgresGrammer;
use DigitLab\SearchParser\Lexers\Lexer;
use DigitLab\SearchParser\Lexers\LuceneLexer;
use DigitLab\SearchParser\Parsers\LuceneParser;
use DigitLab\SearchParser\Parsers\Parser;
use Illuminate\Support\ServiceProvider;

class SearchParserServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLexer();

        $this->registerParser();

        $this->registerGrammer();
    }

    /**
     * Register the Lexer in the container instance.
     */
    protected function registerLexer()
    {
        $lexer = $this->app['config']->get('search.lexer', LuceneLexer::class);

        $this->app->bindIf(Lexer::class, $lexer);
    }

    /**
     * Register the Parser in the container instance.
     */
    protected function registerParser()
    {
        $parser = $this->app['config']->get('search.parser', LuceneParser::class);

        $this->app->bind(Parser::class, $parser);
    }

    protected function registerGrammer()
    {
        $generator = $this->app['config']->get('search.generator', PostgresGrammer::class);

        $this->app->bind(Grammer::class, $generator);
    }
}