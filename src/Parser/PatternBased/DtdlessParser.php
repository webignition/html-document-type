<?php

namespace webignition\HtmlDocumentType\Parser\PatternBased;

/**
 * Parse a well-formed public HTML document type that does not use a DTD
 *
 * Doctype string must be of the form:
 * <!DOCTYPE html>
 */
class DtdlessParser extends AbstractPatternBasedParser
{
    const PATTERN = '/^<![Dd][Oo][Cc][Tt][Yy][Pp][Ee]\s+[Hh][Tt][Mm][Ll]\s*>$/';

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
            self::PART_PUBLIC_SYSTEM_KEYWORD => null,
            self::PART_FPI => null,
            self::PART_URI => null,
        ];
    }
}
