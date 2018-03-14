<?php

namespace webignition\HtmlDocumentType;

interface HtmlDocumentTypeInterface
{
    const PUBLIC_SYSTEM_KEYWORD_PUBLIC = 'PUBLIC';
    const PUBLIC_SYSTEM_KEYWORD_SYSTEM = 'SYSTEM';

    /**
     * @return string
     */
    public function getPublicSystemKeyword();

    /**
     * @return string
     */
    public function getFpi();

    /**
     * @return string
     */
    public function getUri();

    /**
     * @return string
     */
    public function __toString();
}
