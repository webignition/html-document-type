<?php

namespace webignition\HtmlDocumentType\Parser\PatternBased;

use webignition\HtmlDocumentType\Parser\ParserInterface;

/**
 * Parse a well-formed public HTML document type that conforms to a subset
 */
abstract class AbstractPatternBasedParser implements ParserInterface
{
    /**
     * @return string
     */
    abstract protected function getPattern();

    /**
     * {@inheritdoc}
     */
    public function matches($docTypeString)
    {
        return preg_match($this->getPattern(), $docTypeString) > 0;
    }
}
