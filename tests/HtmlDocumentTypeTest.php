<?php

namespace webignition\Tests\HtmlDocumentType;

use webignition\HtmlDocumentType\HtmlDocumentType;
use webignition\HtmlDocumentType\HtmlDocumentTypeInterface;

class HtmlDocumentTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param string|null $publicSystemKeyword
     * @param string|null $fpi
     * @param string|null $uri
     * @param string $expectedDocTypeString
     */
    public function testCreate($publicSystemKeyword, $fpi, $uri, $expectedDocTypeString)
    {
        $htmlDocumentType = new HtmlDocumentType($fpi, $uri, $publicSystemKeyword);

        $this->assertEquals($expectedDocTypeString, (string)$htmlDocumentType);
    }

    /**
     * @return array
     */
    public function createDataProvider()
    {
        return [
            'no public/system keyword, no fpi, no uri, (html5 example)' => [
                'publicSystemKeyword' => null,
                'fpi' => null,
                'uri' => null,
                'expectedDocTypeString' => '<!DOCTYPE html>',
            ],
            'system, no fpi, has uri, (html5 legacy-compat example)' => [
                'publicSystemKeyword' => HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_SYSTEM,
                'fpi' => null,
                'uri' => 'about:legacy-compat',
                'expectedDocTypeString' => '<!DOCTYPE html SYSTEM "about:legacy-compat">',
            ],
            'public, has fpi, has uri, (html4.01 example)' => [
                'publicSystemKeyword' => HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                'fpi' => '-//W3C//DTD HTML 4.01//EN',
                'uri' => 'http://www.w3.org/TR/html4/strict.dtd',
                'expectedDocTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" '
                    .'"http://www.w3.org/TR/html4/strict.dtd">',
            ],
        ];
    }

    public function testGetPublicSystemKeyword()
    {
        $publicSystemKeyword = HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC;

        $htmlDocumentType = new HtmlDocumentType(null, null, $publicSystemKeyword);

        $this->assertEquals($publicSystemKeyword, $htmlDocumentType->getPublicSystemKeyword());
    }

    public function testGetFpi()
    {
        $fpi = 'foo';

        $htmlDocumentType = new HtmlDocumentType($fpi);

        $this->assertEquals($fpi, $htmlDocumentType->getFpi());
    }

    public function testGetUri()
    {
        $uri = 'http://foo.example.com/foo.dtd';

        $htmlDocumentType = new HtmlDocumentType(null, $uri);

        $this->assertEquals($uri, $htmlDocumentType->getUri());
    }
}
