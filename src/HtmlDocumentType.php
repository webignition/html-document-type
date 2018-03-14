<?php

namespace webignition\HtmlDocumentType;

class HtmlDocumentType implements HtmlDocumentTypeInterface
{
    /**
     * @var string
     */
    private $publicSystemKeyword;

    /**
     * @var string
     */
    private $fpi;

    /**
     * @var string
     */
    private $uri;

    /**
     * @param string|null $fpi
     * @param string|null $uri
     * @param string|null $publicSystemKeyword
     */
    public function __construct($fpi = null, $uri = null, $publicSystemKeyword = null)
    {
        $this->fpi = $fpi;
        $this->uri = $uri;
        $this->publicSystemKeyword = $publicSystemKeyword;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicSystemKeyword()
    {
        return $this->publicSystemKeyword;
    }

    /**
     * {@inheritdoc}
     */
    public function getFpi()
    {
        return $this->fpi;
    }

    /**
     * {@inheritdoc}
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $docType = '<!DOCTYPE html';

        if (!empty($this->publicSystemKeyword)) {
            $docType .= ' ' . $this->publicSystemKeyword;
        }

        if (!empty($this->fpi)) {
            $docType .= ' "' . $this->fpi . '"';
        }

        if (!empty($this->uri)) {
            $docType .= ' "' . $this->uri . '"';
        }

        $docType .= '>';

        return $docType;
    }
}
