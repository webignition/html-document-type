<?php

namespace webignition\HtmlDocumentType\Parser;

use webignition\HtmlDocumentType\Parser\PatternBased\CompleteParser;
use webignition\HtmlDocumentType\Parser\PatternBased\DtdlessParser;
use webignition\HtmlDocumentType\Parser\PatternBased\Html5LegacyCompatParser;
use webignition\HtmlDocumentType\Parser\PatternBased\UrilessParser;

class Parser implements ParserInterface
{
    /**
     * @var ParserInterface[]
     */
    private $patternBasedParsers = array();

    public function __construct()
    {
        $this->patternBasedParsers[] = new DtdlessParser();
        $this->patternBasedParsers[] = new CompleteParser('"', '"');
        $this->patternBasedParsers[] = new CompleteParser("'", "'");
        $this->patternBasedParsers[] = new UrilessParser("'");
        $this->patternBasedParsers[] = new UrilessParser('"');
        $this->patternBasedParsers[] = new CompleteParser("'", '"');
        $this->patternBasedParsers[] = new CompleteParser('"', "'");
        $this->patternBasedParsers[] = new Html5LegacyCompatParser();
    }

    /**
     * @param string $docTypeString
     *
     * @return bool
     */
    public function matches($docTypeString)
    {
        try {
            $this->getPatternBasedParser($docTypeString);
        } catch (\RuntimeException $runtimeException) {
            return false;
        }

        return true;
    }

    /**
     * @param string $docTypeString
     *
     * @return array
     */
    public function parse($docTypeString)
    {
        $patternBasedParser = $this->getPatternBasedParser($docTypeString);

        return $patternBasedParser->parse($docTypeString);
    }

    /**
     * @param string $docTypeString
     *
     * @return ParserInterface
     */
    private function getPatternBasedParser($docTypeString)
    {
        foreach ($this->patternBasedParsers as $parser) {
            if ($parser->matches($docTypeString)) {
                return $parser;
            }
        }

        throw new \RuntimeException('No matching parser found for: "' . $docTypeString . '"', 1);
    }
}
