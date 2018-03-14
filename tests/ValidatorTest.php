<?php

namespace webignition\Tests\HtmlDocumentType\Validator;

use webignition\HtmlDocumentType\Factory;
use webignition\HtmlDocumentType\HtmlDocumentType;
use webignition\HtmlDocumentType\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->validator = new Validator();
    }

    /**
     * @dataProvider docTypeDataProvider
     *
     * @param HtmlDocumentType $documentType
     */
    public function testIsValidSuccessStrictMode(HtmlDocumentType $documentType)
    {
        $this->assertTrue($this->validator->isValid($documentType));
    }

    /**
     * @dataProvider isValidSuccessLooseModeDataProvider
     *
     * @param HtmlDocumentType $documentType
     */
    public function testIsValidSuccessLooseMode(HtmlDocumentType $documentType)
    {
        $this->validator->setMode(Validator::MODE_IGNORE_FPI_URI_VALIDITY);

        $this->assertTrue($this->validator->isValid($documentType));
    }

    /**
     * @return array
     */
    public function isValidSuccessLooseModeDataProvider()
    {
        $dataSet = $this->docTypeDataProvider();

        foreach ($dataSet as $key => $testData) {
            /* @var HtmlDocumentType $documentType */
            $documentType = $testData['documentType'];

            $uri = $documentType->getUri();

            if (null === $uri) {
                unset($dataSet[$key]);
            } else {
                $docTypeString = preg_replace(
                    '/http:\/\/.+$/i',
                    'http://foo.example.com/foo.dtd">',
                    (string)$documentType
                );

                $documentType = Factory::createFromDocTypeString($docTypeString);

                $testData['documentType'] = $documentType;
                $dataSet[$key] = $testData;
            }
        }

        return $dataSet;
    }

    /**
     * @dataProvider isValidFailureIncorrectFpiStrictModeDataProvider
     *
     * @param HtmlDocumentType $documentType
     */
    public function testIsValidFailureIncorrectFpiStrictMode(HtmlDocumentType $documentType)
    {
        $validator = new Validator();

        $this->assertFalse($validator->isValid($documentType));
    }

    /**
     * @dataProvider isValidFailureIncorrectFpiRelaxedModeDataProvider
     *
     * @param HtmlDocumentType $documentType
     */
    public function testIsValidFailureIncorrectFpiRelaxedMode(HtmlDocumentType $documentType)
    {
        $validator = new Validator();
        $validator->setMode(Validator::MODE_IGNORE_FPI_URI_VALIDITY);

        $this->assertFalse($validator->isValid($documentType));
    }

    /**
     * @return array
     */
    public function isValidFailureIncorrectFpiRelaxedModeDataProvider()
    {
        $dataSet = $this->isValidFailureIncorrectFpiStrictModeDataProvider();

        foreach ($dataSet as $key => $testData) {
            /* @var HtmlDocumentType $documentType */
            $documentType = $testData['documentType'];

            $uri = $documentType->getUri();

            if (null === $uri) {
                unset($dataSet[$key]);
            }
        }

        return $dataSet;
    }

    /**
     * @return array
     */
    public function isValidFailureIncorrectFpiStrictModeDataProvider()
    {
        $dataSet = $this->docTypeDataProvider();

        foreach ($dataSet as $key => $testData) {
            /* @var HtmlDocumentType $documentType */
            $documentType = $testData['documentType'];

            $fpi = $documentType->getFpi();

            if (null === $fpi) {
                unset($dataSet[$key]);
            } else {
                $docTypeString = str_replace('DTD', 'foo', (string)$documentType);
                $documentType = Factory::createFromDocTypeString($docTypeString);

                $testData['documentType'] = $documentType;
                $dataSet[$key] = $testData;
            }
        }

        return $dataSet;
    }

    /**
     * @return array
     */
    public function docTypeDataProvider()
    {
        return [
            'html-2' => [
                'documentType' => Factory::createFromDocTypeString('<!DOCTYPE html PUBLIC "-//IETF//DTD HTML//EN">'),
            ],
            'html-32' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">'
                ),
            ],
            'html-4-strict' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC '
                    .'"-//W3C//DTD HTML 4.0//EN" '
                    .'"http://www.w3.org/TR/1998/REC-html40-19980424/strict.dtd">'
                ),
            ],
            'html-4-transitional' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC '
                    .'"-//W3C//DTD HTML 4.0 Transitional//EN" '
                    .'"http://www.w3.org/TR/1998/REC-html40-19980424/loose.dtd">'
                ),
            ],
            'html-4-frameset' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Frameset//EN" '
                    .'"http://www.w3.org/TR/1998/REC-html40-19980424/frameset.dtd">'
                ),
            ],
            'html-401-strict' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" '
                    .'"http://www.w3.org/TR/html4/strict.dtd">'
                ),
            ],
            'html-401-transitional' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" '
                    .'"http://www.w3.org/TR/html4/loose.dtd">'
                ),
            ],
            'html-401-frameset' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" '
                    .'"http://www.w3.org/TR/html4/frameset.dtd">'
                ),
            ],
            'html-5' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html>'
                ),
            ],
            'html-5-legacy-compat' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html SYSTEM "about:legacy-compat">'
                ),
            ],
            'xhtml-1-strict' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" '
                    .'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'
                ),
            ],
            'xhtml-1-transitional' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" '
                    .'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
                ),
            ],
            'xhtml-1-frameset' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" '
                    .'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">'
                ),
            ],
            'xhtml-11' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" '
                    .'"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'
                ),
            ],
            'xhtml+basic-1' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.0//EN" '
                    .'"http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd">'
                ),
            ],
            'xhtml+basic-11' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" '
                    .'"http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">'
                ),
            ],
            'xhtml+print-1' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML-Print 1.0//EN" '
                    .'"http://www.w3.org/TR/xhtml-print/DTD/xhtml-print10.dtd">'
                ),
            ],
            'xhtml+mobile-1' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" '
                    .'"http://www.wapforum.org/DTD/xhtml-mobile10.dtd">'
                ),
            ],
            'xhtml+mobile-11' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.1//EN" '
                    .'"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile11.dtd">'
                ),
            ],
            'xhtml+mobile-12' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" '
                    .'"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">'
                ),
            ],
            'xhtml+rdfa-1' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" '
                    .'"http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">'
                ),
            ],
            'xhtml+rdfa-11' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.1//EN" '
                    .'"http://www.w3.org/MarkUp/DTD/xhtml-rdfa-2.dtd">'
                ),
            ],
            'xhtml+aria-1' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+ARIA 1.0//EN" '
                    .'"http://www.w3.org/WAI/ARIA/schemata/xhtml-aria-1.dtd">'
                ),
            ],
            'html+aria-401' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML+ARIA 1.0//EN" '
                    .'"http://www.w3.org/WAI/ARIA/schemata/html4-aria-1.dtd">'
                ),
            ],
            'html+rdfa-401-1' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01+RDFa 1.0//EN" '
                    .'"http://www.w3.org/MarkUp/DTD/html401-rdfa-1.dtd">'
                ),
            ],
            'html+rdfa-401-11' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01+RDFa 1.1//EN" '
                    .'"http://www.w3.org/MarkUp/DTD/html401-rdfa11-1.dtd">'
                ),
            ],
            'html+rdfalite-401-11' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01+RDFa Lite 1.1//EN" '
                    .'"http://www.w3.org/MarkUp/DTD/html401-rdfalite11-1.dtd">'
                ),
            ],
            'html+iso15445-1' => [
                'documentType' => Factory::createFromDocTypeString(
                    '<!DOCTYPE html PUBLIC "ISO/IEC 15445:2000//DTD HTML//EN">'
                ),
            ],
        ];
    }
}
