<?php

namespace webignition\HtmlDocumentType;

use webignition\HtmlDocumentType\Parser\Parser;
use webignition\HtmlDocumentType\Parser\ParserInterface;

class Factory
{
    const EXCEPTION_EMPTY_DOCTYPE_MESSAGE = 'Empty doctype';
    const EXCEPTION_EMPTY_DOCTYPE_CODE = 1;
    const EXCEPTION_UNKNOWN_DOCTYPE_MESSAGE = 'Unknown doctype "%s"';
    const EXCEPTION_UNKNOWN_DOCTYPE_CODE = 2;

    /**
     * @param string $docTypeString
     *
     * @return HtmlDocumentType
     */
    public static function createFromDocTypeString($docTypeString)
    {
        $docTypeString = trim($docTypeString);
        if ('' === $docTypeString) {
            throw new \InvalidArgumentException(
                self::EXCEPTION_EMPTY_DOCTYPE_MESSAGE,
                self::EXCEPTION_EMPTY_DOCTYPE_CODE
            );
        }

        $parser = new Parser();

        if (!$parser->matches($docTypeString)) {
            throw new \InvalidArgumentException(
                sprintf(self::EXCEPTION_UNKNOWN_DOCTYPE_MESSAGE, $docTypeString),
                self::EXCEPTION_UNKNOWN_DOCTYPE_CODE
            );
        }

        $docTypeParts = $parser->parse($docTypeString);

        return new HtmlDocumentType(
            $docTypeParts[ParserInterface::PART_FPI],
            $docTypeParts[ParserInterface::PART_URI],
            $docTypeParts[ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD]
        );
    }

    /**
     * @param string $html
     *
     * @return HtmlDocumentType
     */
    public static function createFromHtmlDocument($html)
    {
        return self::createFromDocTypeString(Extractor::extract($html));
    }
}
