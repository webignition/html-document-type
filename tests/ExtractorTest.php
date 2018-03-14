<?php

namespace webignition\Tests\HtmlDocumentType\Extractor;

use webignition\HtmlDocumentType\Extractor;
use webignition\Tests\HtmlDocumentType\Helper\DoctypeList;
use webignition\Tests\HtmlDocumentType\Helper\HtmlDocumentFactory;

class ExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDocumentTypeStringDataProvider
     *
     * @param string $html
     * @param string $expectedDocumentTypeString
     */
    public function testExtract($html, $expectedDocumentTypeString)
    {
        $this->assertEquals($expectedDocumentTypeString, Extractor::extract($html));
    }

    /**
     * @return array
     */
    public function getDocumentTypeStringDataProvider()
    {
        $hasDocTypeDataSet = [];

        $docTypeList = DoctypeList::create();
        foreach ($docTypeList as $key => $docType) {
            $expectedDocType = $this->createExpectedDocTypeFromTestDocType($docType);

            $hasDocTypeDataSet['has doctype: ' . $key] = [
                'html' => HtmlDocumentFactory::create($docType),
                'expectedDocumentTypeString' => $expectedDocType,
                'expectedHasDocumentType' => true,
            ];
        }

        return array_merge(
            [
                'empty html' => [
                    'html' => '',
                    'expectedDocumentTypeString' => '',
                ],
                'no doctype' => [
                    'html' => HtmlDocumentFactory::create(''),
                    'expectedDocumentTypeString' => '',
                ],
                'doctype, no html' => [
                    'html' => '<!DOCTYPE html> foo',
                    'expectedDocumentTypeString' => '<!DOCTYPE html>',
                ],
                'doctype in html body' => [
                    'html' => HtmlDocumentFactory::create(
                        '<!DOCTYPE html>',
                        HtmlDocumentFactory::TEMPLATE_KEY_DOCTYPE_IN_HTML_BODY
                    ),
                    'expectedDocumentTypeString' => '',
                ],
                'preceding multi-line comment' => [
                    'html' => HtmlDocumentFactory::create(
                        "<!--[if IE ]>\nFoo\nBar<![endif]-->\n" . '<!DOCTYPE html>'
                    ),
                    'expectedDocumentTypeString' => '<!DOCTYPE html>',
                ],
                'preceding single-line comment' => [
                    'html' => HtmlDocumentFactory::create(
                        "<!--[if IE ]>Foo<![endif]-->\n" . '<!DOCTYPE html>'
                    ),
                    'expectedDocumentTypeString' => '<!DOCTYPE html>',
                ],
                'same-line multi-line comment' => [
                    'html' => HtmlDocumentFactory::create(
                        "<!--[if IE ]>\nFoo\nBar<![endif]-->" . '<!DOCTYPE html>'
                    ),
                    'expectedDocumentTypeString' => '<!DOCTYPE html>',
                ],
                'same-line single-line comment' => [
                    'html' => HtmlDocumentFactory::create(
                        "<!--[if IE ]>Foo<![endif]-->" . '<!DOCTYPE html>'
                    ),
                    'expectedDocumentTypeString' => '<!DOCTYPE html>',
                ],
                'preceding xml prefix' => [
                    'html' => HtmlDocumentFactory::create(
                        '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<!DOCTYPE html>'
                    ),
                    'expectedDocumentTypeString' => '<!DOCTYPE html>',
                ],
                'same-line xml prefix' => [
                    'html' => HtmlDocumentFactory::create(
                        '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE html>'
                    ),
                    'expectedDocumentTypeString' => '<!DOCTYPE html>',
                ],
                'blank lines preceding' => [
                    'html' => HtmlDocumentFactory::create(
                        "\n\n\n" . '<!DOCTYPE html>'
                    ),
                    'expectedDocumentTypeString' => '<!DOCTYPE html>',
                ],
            ],
            $hasDocTypeDataSet
        );
    }

    private function createExpectedDocTypeFromTestDocType($testDocType)
    {
        $expectedDocType = $testDocType;

        // Expect <!DOCTYPE prefix to be uppercase
        $expectedDocType = preg_replace('/^<!doctype/i', '<!DOCTYPE', $expectedDocType);

        // Expect root element to be lowercase (spec doesn't care about root element case)
        $expectedDocType = preg_replace_callback(
            '/^<!doctype\s[a-z]+/i',
            function (array $matches) {
                $match = $matches[0];
                $matchParts = explode(' ', $match);
                $rootElement = $matchParts[1];

                return $matchParts[0] . ' ' . strtolower($rootElement);
            },
            $expectedDocType
        );

        // Expect DTD to be all on one line
        $expectedDocType = str_replace(["\n"], '', $expectedDocType);
        $expectedDocType = preg_replace('/ >$/', '>', $expectedDocType);

        return $expectedDocType;
    }
}
