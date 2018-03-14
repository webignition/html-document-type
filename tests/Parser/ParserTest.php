<?php

namespace webignition\Tests\HtmlDocumentType\Parser;

use webignition\HtmlDocumentType\HtmlDocumentTypeInterface;
use webignition\HtmlDocumentType\Parser\Parser;
use webignition\HtmlDocumentType\Parser\ParserInterface;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->parser = new Parser();
    }

    public function testParseNoMatchingParser()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No matching parser found for: "<!DOCTYPE html PUBLIC>"');
        $this->expectExceptionCode(1);

        $this->parser->parse('<!DOCTYPE html PUBLIC>');
    }

    public function testMatchesNoMatchingParser()
    {
        $this->assertFalse($this->parser->matches('<!DOCTYPE html PUBLIC>'));
    }

    /**
     * @dataProvider parseDataProvider
     *
     * @param string $docTypeString
     * @param array $expectedDocTypeParts
     */
    public function testParseSuccess($docTypeString, array $expectedDocTypeParts)
    {
        $this->assertTrue($this->parser->matches($docTypeString));
        $this->assertEquals($expectedDocTypeParts, $this->parser->parse($docTypeString));
    }

    /**
     * @return array
     */
    public function parseDataProvider()
    {
        return [
            'html-2' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//IETF//DTD HTML//EN">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//IETF//DTD HTML//EN',
                    ParserInterface::PART_URI => null,
                ],
            ],
            'html-32' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD HTML 3.2 Final//EN',
                    ParserInterface::PART_URI => null,
                ],
            ],
            'html-4-strict' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC '
                    .'"-//W3C//DTD HTML 4.0//EN" '
                    .'"http://www.w3.org/TR/1998/REC-html40-19980424/strict.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD HTML 4.0//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/TR/1998/REC-html40-19980424/strict.dtd',
                ],
            ],
            'html-4-transitional' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC '
                    .'"-//W3C//DTD HTML 4.0 Transitional//EN" '
                    .'"http://www.w3.org/TR/1998/REC-html40-19980424/loose.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD HTML 4.0 Transitional//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/TR/1998/REC-html40-19980424/loose.dtd',
                ],
            ],
            'html-4-frameset' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Frameset//EN" '
                    .'"http://www.w3.org/TR/1998/REC-html40-19980424/frameset.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD HTML 4.0 Frameset//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/TR/1998/REC-html40-19980424/frameset.dtd',
                ],
            ],
            'html-401-strict' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" '
                    .'"http://www.w3.org/TR/html4/strict.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD HTML 4.01//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/TR/html4/strict.dtd',
                ],
            ],
            'html-401-transitional' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" '
                    .'"http://www.w3.org/TR/html4/loose.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD HTML 4.01 Transitional//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/TR/html4/loose.dtd',
                ],
            ],
            'html-401-frameset' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" '
                    .'"http://www.w3.org/TR/html4/frameset.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD HTML 4.01 Frameset//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/TR/html4/frameset.dtd',
                ],
            ],
            'html-5' => [
                'docTypeString' => '<!DOCTYPE html>',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD => null,
                    ParserInterface::PART_FPI => null,
                    ParserInterface::PART_URI => null,
                ],
            ],
            'html-5-legacy-compat' => [
                'docTypeString' => '<!DOCTYPE html SYSTEM "about:legacy-compat">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_SYSTEM,
                    ParserInterface::PART_FPI => null,
                    ParserInterface::PART_URI => 'about:legacy-compat',
                ],
            ],
            'xhtml-1-strict' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" '
                    .'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD XHTML 1.0 Strict//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd',
                ],
            ],
            'xhtml-1-transitional' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" '
                    .'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD XHTML 1.0 Transitional//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd',
                ],
            ],
            'xhtml-1-frameset' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" '
                    .'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD XHTML 1.0 Frameset//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd',
                ],
            ],
            'xhtml-11' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" '
                    .'"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD XHTML 1.1//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd',
                ],
            ],
            'xhtml+basic-1' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.0//EN" '
                    .'"http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD XHTML Basic 1.0//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd',
                ],
            ],
            'xhtml+basic-11' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" '
                    .'"http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD XHTML Basic 1.1//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd',
                ],
            ],
            'xhtml+print-1' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML-Print 1.0//EN" '
                    .'"http://www.w3.org/TR/xhtml-print/DTD/xhtml-print10.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD XHTML-Print 1.0//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/TR/xhtml-print/DTD/xhtml-print10.dtd',
                ],
            ],
            'xhtml+mobile-1' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" '
                    .'"http://www.wapforum.org/DTD/xhtml-mobile10.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//WAPFORUM//DTD XHTML Mobile 1.0//EN',
                    ParserInterface::PART_URI => 'http://www.wapforum.org/DTD/xhtml-mobile10.dtd',
                ],
            ],
            'xhtml+mobile-11' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.1//EN" '
                    .'"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile11.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//WAPFORUM//DTD XHTML Mobile 1.1//EN',
                    ParserInterface::PART_URI => 'http://www.openmobilealliance.org/tech/DTD/xhtml-mobile11.dtd',
                ],
            ],
            'xhtml+mobile-12' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" '
                    .'"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//WAPFORUM//DTD XHTML Mobile 1.2//EN',
                    ParserInterface::PART_URI => 'http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd',
                ],
            ],
            'xhtml+rdfa-1' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" '
                    .'"http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD XHTML+RDFa 1.0//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd',
                ],
            ],
            'xhtml+rdfa-11' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.1//EN" '
                    .'"http://www.w3.org/MarkUp/DTD/xhtml-rdfa-2.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD XHTML+RDFa 1.1//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/MarkUp/DTD/xhtml-rdfa-2.dtd',
                ],
            ],
            'xhtml+aria-1' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+ARIA 1.0//EN" '
                    .'"http://www.w3.org/WAI/ARIA/schemata/xhtml-aria-1.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD XHTML+ARIA 1.0//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/WAI/ARIA/schemata/xhtml-aria-1.dtd',
                ],
            ],
            'html+aria-401' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML+ARIA 1.0//EN" '
                    .'"http://www.w3.org/WAI/ARIA/schemata/html4-aria-1.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD HTML+ARIA 1.0//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/WAI/ARIA/schemata/html4-aria-1.dtd',
                ],
            ],
            'html+rdfa-401-1' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01+RDFa 1.0//EN" '
                    .'"http://www.w3.org/MarkUp/DTD/html401-rdfa-1.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD HTML 4.01+RDFa 1.0//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/MarkUp/DTD/html401-rdfa-1.dtd',
                ],
            ],
            'html+rdfa-401-11' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01+RDFa 1.1//EN" '
                    .'"http://www.w3.org/MarkUp/DTD/html401-rdfa11-1.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD HTML 4.01+RDFa 1.1//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/MarkUp/DTD/html401-rdfa11-1.dtd',
                ],
            ],
            'html+rdfalite-401-11' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01+RDFa Lite 1.1//EN" '
                    .'"http://www.w3.org/MarkUp/DTD/html401-rdfalite11-1.dtd">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => '-//W3C//DTD HTML 4.01+RDFa Lite 1.1//EN',
                    ParserInterface::PART_URI => 'http://www.w3.org/MarkUp/DTD/html401-rdfalite11-1.dtd',
                ],
            ],
            'html+iso15445-1' => [
                'docTypeString' => '<!DOCTYPE html PUBLIC "ISO/IEC 15445:2000//DTD HTML//EN">',
                'expectedDocTypeParts' => [
                    ParserInterface::PART_PUBLIC_SYSTEM_KEYWORD =>
                        HtmlDocumentTypeInterface::PUBLIC_SYSTEM_KEYWORD_PUBLIC,
                    ParserInterface::PART_FPI => 'ISO/IEC 15445:2000//DTD HTML//EN',
                    ParserInterface::PART_URI => null,
                ],
            ],
        ];
    }
}
