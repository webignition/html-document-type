<?php

namespace webignition\Tests\HtmlDocumentType\Extractor;

use webignition\HtmlDocumentType\Factory;
use webignition\HtmlDocumentType\HtmlDocumentType;
use webignition\Tests\HtmlDocumentType\Helper\HtmlDocumentFactory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider createFromHtmlDocumentExceptionDataProvider
     *
     * @param string $html
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     */
    public function testCreateFromHtmlDocumentException($html, $expectedExceptionMessage, $expectedExceptionCode)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        Factory::createFromHtmlDocument($html);
    }

    /**
     * @return array
     */
    public function createFromHtmlDocumentExceptionDataProvider()
    {
        return [
            'empty html' => [
                'html' => '',
                'expectedExceptionMessage' => Factory::EXCEPTION_EMPTY_DOCTYPE_MESSAGE,
                'expectedExceptionCode' => Factory::EXCEPTION_EMPTY_DOCTYPE_CODE,
            ],
            'no doctype' => [
                'html' => HtmlDocumentFactory::create(''),
                'expectedExceptionMessage' => Factory::EXCEPTION_EMPTY_DOCTYPE_MESSAGE,
                'expectedExceptionCode' => Factory::EXCEPTION_EMPTY_DOCTYPE_CODE,
            ],
            'unknown doctype' => [
                'html' => HtmlDocumentFactory::create('<!DOCTYPE html PUBLIC>'),
                'expectedExceptionMessage' => 'Unknown doctype "<!DOCTYPE html PUBLIC>"',
                'expectedExceptionCode' => Factory::EXCEPTION_UNKNOWN_DOCTYPE_CODE,
            ],
            'doctype in html body' => [
                'html' => HtmlDocumentFactory::create(
                    '<!DOCTYPE html>',
                    HtmlDocumentFactory::TEMPLATE_KEY_DOCTYPE_IN_HTML_BODY
                ),
                'expectedExceptionMessage' => Factory::EXCEPTION_EMPTY_DOCTYPE_MESSAGE,
                'expectedExceptionCode' => Factory::EXCEPTION_EMPTY_DOCTYPE_CODE,
            ],
        ];
    }

    /**
     * @dataProvider createFromHtmlDocumentSuccessDataProvider
     *
     * @param string $html
     * @param string $expectedDocumentTypeString
     */
    public function testCreateFromHtmlDocumentSuccess($html, $expectedDocumentTypeString)
    {
        $htmlDocumentType = Factory::createFromHtmlDocument($html);

        $this->assertInstanceOf(HtmlDocumentType::class, $htmlDocumentType);
        $this->assertEquals($expectedDocumentTypeString, (string)$htmlDocumentType);
    }

    /**
     * @return array
     */
    public function createFromHtmlDocumentSuccessDataProvider()
    {
        return [
            'doctype, no html' => [
                'html' => '<!DOCTYPE html> foo',
                'expectedDocumentTypeString' => '<!DOCTYPE html>',
            ],
            'has doctype' => [
                'html' => HtmlDocumentFactory::create(
                    '<!DOCTYPE html>'
                ),
                'expectedDocumentTypeString' => '<!DOCTYPE html>',
            ],
        ];
    }
}
