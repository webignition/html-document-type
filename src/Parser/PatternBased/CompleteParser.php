<?php

namespace webignition\HtmlDocumentType\Parser\PatternBased;

use webignition\HtmlDocumentType\HtmlDocumentTypeInterface;

/**
 * Parse a well-formed public HTML document type that contains a FPI and a URI
 *
 * Doctype string must be of the form:
 * <!DOCTYPE html PUBLIC {{quote}}FPI{{quote}} {{quote}}URI{{quote}}>
 */
class CompleteParser extends AbstractPatternBasedParser
{
    const PATTERN_TEMPLATE = '/^<![Dd][Oo][Cc][Tt][Yy][Pp][Ee]\s+[Hh][Tt][Mm][Ll]\s+[Pp][Uu][Bb][Ll][Ii][Cc]\s+'
    .'{{fpi-quote}}[^{{fpi-quote}}]*{{fpi-quote}}\s+{{uri-quote}}[^{{uri-quote}}]*{{uri-quote}}\s*>$/';

    /**
     * @var string
     */
    private $fpiQuoteCharacter = null;

    /**
     * @var string
     */
    private $uriQuoteCharacter = null;

    /**
     * @param string $fpiQuoteCharacter
     * @param string $uriQuoteCharacter
     */
    public function __construct($fpiQuoteCharacter, $uriQuoteCharacter)
    {
        $this->fpiQuoteCharacter = $fpiQuoteCharacter;
        $this->uriQuoteCharacter = $uriQuoteCharacter;
    }

    /**
     * {@inheritdoc}
     */
    public function getPattern()
    {
        return str_replace([
            '{{fpi-quote}}',
            '{{uri-quote}}'
        ], [
            $this->fpiQuoteCharacter,
            $this->uriQuoteCharacter
        ], self::PATTERN_TEMPLATE);
    }

    /**
     * {@inheritdoc}
     */
    public function parse($docTypeString)
    {
        $fpiQuoteCharacter = $this->fpiQuoteCharacter;

        $firstDoubleQuotePosition = strpos($docTypeString, $fpiQuoteCharacter);
        $secondDoubleQuotePosition = strpos($docTypeString, $fpiQuoteCharacter, $firstDoubleQuotePosition + 1);
        $thirdDoubleQuotePosition = strpos($docTypeString, $this->uriQuoteCharacter, $secondDoubleQuotePosition + 1);
        $lastDoubleQuotePosition = strrpos($docTypeString, $this->uriQuoteCharacter);

        $fpi = substr(
            $docTypeString,
            $firstDoubleQuotePosition + 1,
            $secondDoubleQuotePosition - $firstDoubleQuotePosition - 1
        );
        $uri = trim(substr(
            $docTypeString,
            $thirdDoubleQuotePosition + 1,
            $lastDoubleQuotePosition - $thirdDoubleQuotePosition - 1
        ));

        return [
            self::PART_PUBLIC_SYSTEM_KEYWORD => HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
            self::PART_FPI => $fpi,
            self::PART_URI => $uri,
        ];
    }
}
