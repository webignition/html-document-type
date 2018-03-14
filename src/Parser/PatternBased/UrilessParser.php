<?php

namespace webignition\HtmlDocumentType\Parser\PatternBased;

use webignition\HtmlDocumentType\HtmlDocumentTypeInterface;

/**
 * Parse a well-formed public HTML document type that contains a FPI and no URI
 *
 * Doctype string must be of the form:
 * <!DOCTYPE html PUBLIC {{quote}}FPI{{quote}}>
 */
class UrilessParser extends AbstractPatternBasedParser
{
    const PATTERN_TEMPLATE = '/^<![Dd][Oo][Cc][Tt][Yy][Pp][Ee]\s+[Hh][Tt][Mm][Ll]\s+[Pp][Uu][Bb][Ll][Ii][Cc]\s+'
    .'{{quote}}[^{{quote}}]*{{quote}}\s*>$/';

    /**
     * @var string
     */
    private $fpiQuoteCharacter = null;

    /**
     * @param string $fpiQuoteCharacter
     */
    public function __construct($fpiQuoteCharacter)
    {
        $this->fpiQuoteCharacter = $fpiQuoteCharacter;
    }

    /**
     * {@inheritdoc}
     */
    public function getPattern()
    {
        return str_replace([
            '{{quote}}'
        ], [
            $this->fpiQuoteCharacter,
        ], self::PATTERN_TEMPLATE);
    }

    /**
     * {@inheritdoc}
     */
    public function parse($docTypeString)
    {
        $firstDoubleQuotePosition = strpos($docTypeString, $this->fpiQuoteCharacter);
        $lastDoubleQuotePosition = strrpos($docTypeString, $this->fpiQuoteCharacter);

        $fpi = substr(
            $docTypeString,
            $firstDoubleQuotePosition + 1,
            $lastDoubleQuotePosition - $firstDoubleQuotePosition - 1
        );

        return [
            self::PART_PUBLIC_SYSTEM_KEYWORD => HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
            self::PART_FPI => $fpi,
            self::PART_URI => null,
        ];
    }
}
