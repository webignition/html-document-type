<?php

namespace webignition\HtmlDocumentType\Parser;

/**
 * Parse a well-formed HTML document type into its component parts (PUBLIC/SYSTEM keyword, fpi and uri)
 */
interface ParserInterface
{
    const PART_PUBLIC_SYSTEM_KEYWORD = 'public-system';
    const PART_FPI = 'fpi';
    const PART_URI = 'uri';

    /**
     * @param $docTypeString
     *
     * @return array
     */
    public function parse($docTypeString);

    /**
     * @param string $docTypeString
     *
     * @return bool
     */
    public function matches($docTypeString);
}
