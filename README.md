# For parsing, validating and creating HTML doctype strings (in PHP)
![Master build status](https://travis-ci.org/webignition/html-document-type.svg?branch=master)

Tested against PHP 5.6, 7, 7.1 and 7.2.

Have you ever found yourself thinking:

 - My life would be easier if I could programmatically create a doctype string
 - I'd be happier if I could parse a doctype string into its component parts
 - How can I get a doctype string from a HTML document?
 - Is this doctype string that I'm staring at valid?
 - I really need get the FPI and URI from this document string and I just don't know how!
 
Does that apply to you? If so, my goodness are you in luck. 

- [Usage](#usage)
  - [Creating](#usage-creating)
  - [Parsing](#usage-parsing)
  - [Getting a doctype string from a HTML document](#usage-extracting)
  - [Creating a `HtmlDocumentType` object from a doctype string](#usage-building)
  - [Validating](#usage-validating)
- [Developing](#developing)
  - [Cloning](#developing-cloning)
  - [Testing](#developing-testing)
  
## <a id='usage'>Usage</a>

### <a id='usage-creating'>Creating</a>

```php
<?php

use webignition\HtmlDocumentType\Factory;
use webignition\HtmlDocumentType\HtmlDocumentType;
use webignition\HtmlDocumentType\HtmlDocumentTypeInterface;

$html5DocType = new HtmlDocumentType();
echo $html5DocType;
// <!DOCTYPE html>

$html401StrictDocType = new HtmlDocumentType(
    '-//W3C//DTD HTML 4.01//EN', 
    'http://www.w3.org/TR/html4/strict.dtd',
    HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_SYSTEM
);
echo html401StrictDocType;
// <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
```
### <a id='usage-parsing'>Parsing</a>

If you already know the component parts of a doctype you might as well just create a string.

What is often more useful is taking an existing doctype string and parsing it into its component parts.

```php
<?php

use webignition\HtmlDocumentType\HtmlDocumentTypeInterface;
use webignition\HtmlDocumentType\Parser\Parser;
use webignition\HtmlDocumentType\Parser\ParserInterface;

$parser = new Parser();

$html5DocTypeParts = $parser->parse('<!DOCTYPE html>');
var_dump($html5DocTypeParts);
// array(3) {
//     'public-system' => NULL
//     'fpi' => NULL
//     'uri' => NULL
// }

$html401StrictDocTypeParts = $parser->parse('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">');
var_dump($html401StrictDocTypeParts);
// array(3) {
//   'public-system' => string(6) "PUBLIC"
//   'fpi' => string(25) "-//W3C//DTD HTML 4.01//EN"
//   'uri' => string(37) "http://www.w3.org/TR/html4/strict.dtd"
// }
```

### <a id='usage-extracting'>Getting a doctype string from a HTML document</a>

Parsing a doctype string requires a doctype string. One of the most common places to find a doctype string is in a HTML document.

```php
<?php

use webignition\HtmlDocumentType\Extractor;

$html5DocTypeString = Extractor::extract('<!DOCTYPE html><html>...</html>');
echo $html5DocTypeString;
// <!DOCTYPE html>
```

### <a id='usage-building'>Creating a `HtmlDocumentType` object from a doctype string</a>

You can extract the doctype string from a HTML document, but what do you then do with it? One option is to create a `HtmlDocumentType` object.

```php

use webignition\HtmlDocumentType\Factory;

// Create from a known doctype string
$html5DocumentType = Factory::createFromDocTypeString('<!DOCTYPE html>');
echo $html5DocType;
// <!DOCTYPE html>


// Create from a HTML document
$html5DocumentType = Factory::createFromHtmlDocument('<!DOCTYPE html><html>...</html>');
echo $html5DocType;
// <!DOCTYPE html>
```

### <a id='usage-validating'>Validating</a>

Two of the component parts of a doctype are particularly relevant to validation: the `fpi` and the `uri`. There are known set of valid `fpi` values. For each valid `fpi`, there are a known set of valid `uri` values.

Need to check that your doctype's `fpi` is valid that the corresponding `uri` is valid for that `fpi`?

```php

use webignition\HtmlDocumentType\Factory;
use webignition\HtmlDocumentType\Validator;

$validator = new Validator();

$html5DocumentType = Factory::createFromDocTypeString('<!DOCTYPE html>');
$validator->isValid($html5DocumentType);
// true

$html401StrictDocumentType = Factory::createFromDocTypeString('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">');
$validator->isValid($html401StrictDocumentType);
// true

$invalidDocumentType = new HtmlDocumentType('invalid FPI');
$validator->isValid($invalidDocumentType);
// false

// If the uri doesn't match what is expected for the fpi, the doctype is considered invalid
$laxHtml401StrictDocumentType = Factory::createFromDocTypeString('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "foo">');
$validator->isValid($laxHtml401StrictDocumentType);
// false

// You can relax the strictness of validity. This is useful in cases where a doctype string uses the uri of a draft version of a HTML spec (the doctype string was once valid but is no longer)
$validator->setMode(Validator::MODE_IGNORE_FPI_URI_VALIDITY);
$validator->isValid($laxHtml401StrictDocumentType);
// true

// Relaxing validity strictness has no impact on invalid fpi values
$validator->setMode(Validator::MODE_IGNORE_FPI_URI_VALIDITY);
$validator->isValid($invalidDocumentType);
// false
```

## <a id='developing'>Developing</a>

### <a id='developing-cloning'>Cloning</a>

You will need `git` to clone and `composer` to retrieve dependencies. I'm assuming both are in your path. Adjust as required.

```bash
git clone git@github.com:webignition/html-document-type.git
cd html-document-type
composer install
```

### <a id='developing-testing'>Testing</a>

This project uses `phpcs` to check for PSR2 coding standards and `phpunit` for running the tests. Both are installed by composer as dev dependencies.

Composer scripts are present to run CS checks, to run tests or to run both.

```bash
# Coding standards checks
composer cs
> ./vendor/bin/phpcs src tests --colors --standard=PSR2

# Tests
composer test
> ./vendor/bin/phpunit --colors=always
PHPUnit 5.7.27 by Sebastian Bergmann and contributors.

................................ (and so on)

# Coding standards checks then tests
 composer ci
> ./vendor/bin/phpcs src tests --colors --standard=PSR2
> ./vendor/bin/phpunit --colors=always
PHPUnit 5.7.27 by Sebastian Bergmann and contributors.

.................................... (and so on)
```
