<?php

namespace webignition\HtmlDocumentType\Parser\PatternBased;

use webignition\HtmlDocumentType\HtmlDocumentTypeInterface;

/**
 * Parse a well-formed public HTML document type that is present for HTML5 compatibility
 *
 * Doctype string must be of the form:
 * <!DOCTYPE html "about:legacy-compat">
 */
class Html5LegacyCompatParser extends AbstractPatternBasedParser
{
    const PATTERN = '/^<![Dd][Oo][Cc][Tt][Yy][Pp][Ee]\s+[Hh][Tt][Mm][Ll]\s*[Ss][Yy][Ss][Tt][Ee][Mm]\s'
    .'"about:legacy-compat">$/';

    /**
     * {@inheritdoc}
     */
    public function getPattern()
    {
        return self::PATTERN;
    }

    /**
     * {@inheritdoc}
     */
    public function parse($docTypeString)
    {
        return [
            self::PART_PUBLIC_SYSTEM_KEYWORD => HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_SYSTEM,
            self::PART_FPI => null,
            self::PART_URI => 'about:legacy-compat',
        ];
    }
}
