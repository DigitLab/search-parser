# Search Parser

[![StyleCI](https://styleci.io/repos/56829791/shield?style=flat)](https://styleci.io/repos/56829791)
[![Build Status](https://travis-ci.org/DigitLab/search-parser.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/digitlab/search-parser/downloads)](https://packagist.org/packages/digitlab/search-parser)
[![Latest Stable Version](https://poser.pugx.org/digitlab/search-parser/v/stable)](https://packagist.org/packages/digitlab/search-parser)
[![License](https://poser.pugx.org/digitlab/search-parser/license)](https://packagist.org/packages/digitlab/search-parser)

Parses Lucene/Google style search strings into PostgresSQL full text query string.

## Installation

Install using composer:

```bash
composer require digitlab/search-parser
```

Add the service provider in app/config/app.php:

```php
DigitLab\SearchParser\SearchParserServiceProvider::class,
```

## Usage

### Basic Usage
To just parse a full text query you can simply use SearchParser:

```php
$parser = new SearchParser();
$filters = $parser->parse('string to parse');
```

will produce

```php
[
    'search' => 'string&to&parse'
]
```

### Filters

To handle filters you need to extend SearchParser and add a handle function or add a pass through filter:

```php
class CustomSearchParser extends SearchParser
{
    /**
     * The filters that should be returned without handlers.
     *
     * @var array
     */
    protected $passthruFilters = ['other'];

    /**
     * Handle the state filter.
     *
     * @param mixed $state
     * @return array
     */
    protected function handleState($state)
    {
        return ['some' => $state];
    }
}
```

```php
$parser = new CustomSearchParser();
$filters = $parser->parse('state:pending other:string string to parse');
```

will produce

```php
[
    'search' => 'string&to&parse',
    'some' => 'pending',
    'other' => 'string'
]
```

### Custom Query Key

You can customise the array key of the query by overriding the ```$queryName``` variable in your custom class.

```php
class CustomSearchParser extends SearchParser
{
    /**
     * The name of the query in the result array.
     *
     * @var string
     */
    protected $queryName = 'other';
}
```

```php
$parser = new CustomSearchParser();
$filters = $parser->parse('string to parse');
```

will produce

```php
[
    'other' => 'string&to&parse'
]
```

## License

Adaptive View is licensed under [The MIT License (MIT)](LICENSE).