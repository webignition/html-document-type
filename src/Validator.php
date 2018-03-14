<?php

namespace webignition\HtmlDocumentType;

use webignition\HtmlDocumentType\HtmlDocumentType;

class Validator
{
    const DOCTYPE_HTML5_LEGACY_COMPAT_URI = 'about:legacy-compat';
    const MODE_STRICT = 'strict';
    const MODE_IGNORE_FPI_URI_VALIDITY = 'loose';

    const FPI_HTML_2 = '-//IETF//DTD HTML//EN';
    const FPI_HTML_2_ALTERNATIVE = '-//IETF//DTD HTML 2.0//EN';
    const FPI_HTML_3_2 = '-//W3C//DTD HTML 3.2 Final//EN';
    const FPI_HTML_3_2_ALTERNATIVE1 = '-//W3C//DTD HTML 3.2//EN';
    const FPI_HTML_3_2_ALTERNATIVE2 = '-//W3C//DTD HTML 3.2 Draft//EN';
    const FPI_HTML_4_STRICT = '-//W3C//DTD HTML 4.0//EN';
    const FPI_HTML_4_TRANSITIONAL = '-//W3C//DTD HTML 4.0 Transitional//EN';
    const FPI_HTML_4_FRAMESET = '-//W3C//DTD HTML 4.0 Frameset//EN';
    const FPI_HTML_4_01_STRICT = '-//W3C//DTD HTML 4.01//EN';
    const FPI_HTML_4_01_TRANSITIONAL = '-//W3C//DTD HTML 4.01 Transitional//EN';
    const FPI_HTML_4_01_FRAMESET = '-//W3C//DTD HTML 4.01 Frameset//EN';
    const FPI_HTML_4_01_ARIA = '-//W3C//DTD HTML+ARIA 1.0//EN';
    const FPI_HTML_4_01_RDFA_1 = '-//W3C//DTD HTML 4.01+RDFa 1.0//EN';
    const FPI_HTML_4_01_RDFA_1_1 = '-//W3C//DTD HTML 4.01+RDFa 1.1//EN';
    const FPI_HTML_4_01_RDFALITE_1_1 = '-//W3C//DTD HTML 4.01+RDFa Lite 1.1//EN';
    const FPI_HTML_ISO_15445 = 'ISO/IEC 15445:2000//DTD HTML//EN';
    const FPI_HTML_ISO_15445_ALTERNATIVE = 'ISO/IEC 15445:2000//DTD HyperText Markup Language//EN';
    const FPI_XHTML_1_STRICT = '-//W3C//DTD XHTML 1.0 Strict//EN';
    const FPI_XHTML_1_TRANSITIONAL = '-//W3C//DTD XHTML 1.0 Transitional//EN';
    const FPI_XHTML_1_FRAMESET = '-//W3C//DTD XHTML 1.0 Frameset//EN';
    const FPI_XHTML_1_BASIC = '-//W3C//DTD XHTML Basic 1.0//EN';
    const FPI_XHTML_1_PRINT = '-//W3C//DTD XHTML-Print 1.0//EN';
    const FPI_XHTML_MOBILE_1 = '-//WAPFORUM//DTD XHTML Mobile 1.0//EN';
    const FPI_XHTML_MOBILE_1_1 = '-//WAPFORUM//DTD XHTML Mobile 1.1//EN';
    const FPI_XHTML_MOBILE_1_2 = '-//WAPFORUM//DTD XHTML Mobile 1.2//EN';
    const FPI_XHTML_1_1 = '-//W3C//DTD XHTML 1.1//EN';
    const FPI_XHTML_1_1_BASIC = '-//W3C//DTD XHTML Basic 1.1//EN';
    const FPI_XHTML_RDFA_1 = '-//W3C//DTD XHTML+RDFa 1.0//EN';
    const FPI_XHTML_RDFA_1_1 = '-//W3C//DTD XHTML+RDFa 1.1//EN';
    const FPI_XHTML_ARIA_1 = '-//W3C//DTD XHTML+ARIA 1.0//EN';

    const URI_HTML_4_STRICT = 'http://www.w3.org/TR/1998/REC-html40-19980424/strict.dtd';
    const URI_HTML_4_TRANSITIONAL = 'http://www.w3.org/TR/1998/REC-html40-19980424/loose.dtd';
    const URI_HTML_4_FRAMESET = 'http://www.w3.org/TR/1998/REC-html40-19980424/frameset.dtd';
    const URI_HTML_4_01_STRICT = 'http://www.w3.org/TR/html4/strict.dtd';
    const URI_HTML_4_01_TRANSITIONAL = 'http://www.w3.org/TR/html4/loose.dtd';
    const URI_HTML_4_01_FRAMESET = 'http://www.w3.org/TR/html4/frameset.dtd';
    const URI_HTML_5_LEGACY_COMPAT = 'about:legacy-compat';
    const URI_XHTML_1_STRICT = 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd';
    const URI_XHTML_1_TRANSITIONAL = 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd';
    const URI_XHTML_1_FRAMESET = 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd';
    const URI_XHTML_1_1 = 'http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd';
    const URI_XHTML_BASIC_1 = 'http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd';
    const URI_XHTML_BASIC_1_1 = 'http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd';
    const URI_XHTML_PRINT_1 = 'http://www.w3.org/TR/xhtml-print/DTD/xhtml-print10.dtd';
    const URI_XHTML_MOBILE_1 = 'http://www.wapforum.org/DTD/xhtml-mobile10.dtd';
    const URI_XHTML_MOBILE_1_1 = 'http://www.openmobilealliance.org/tech/DTD/xhtml-mobile11.dtd';
    const URI_XHTML_MOBILE_1_2 = 'http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd';
    const URI_XHTML_RDFA_1 = 'http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd';
    const URI_XHTML_RDFA_1_1 = 'http://www.w3.org/MarkUp/DTD/xhtml-rdfa-2.dtd';
    const URI_XHTML_ARIA_1 = 'http://www.w3.org/WAI/ARIA/schemata/xhtml-aria-1.dtd';
    const URI_HTML_ARIA_4_01_1 = 'http://www.w3.org/WAI/ARIA/schemata/html4-aria-1.dtd';
    const URI_HTML_RDFA_4_01_1 = 'http://www.w3.org/MarkUp/DTD/html401-rdfa-1.dtd';
    const URI_HTML_RDFA_4_01_1_1 = 'http://www.w3.org/MarkUp/DTD/html401-rdfa11-1.dtd';
    const URI_HTML_RDFALITE_4_01_1_1 = 'http://www.w3.org/MarkUp/DTD/html401-rdfalite11-1.dtd';


    /**
     * @var array
     */
    private $fpiList = [
        'html-2' => self::FPI_HTML_2,
        'html-2-alternative' => self::FPI_HTML_2_ALTERNATIVE,
        'html-32' => self::FPI_HTML_3_2,
        'html-32-alternative1' => self::FPI_HTML_3_2_ALTERNATIVE1,
        'html-32-alternative2' => self::FPI_HTML_3_2_ALTERNATIVE2,
        'html-4-strict' => self::FPI_HTML_4_STRICT,
        'html-4-transitional' => self::FPI_HTML_4_TRANSITIONAL,
        'html-4-frameset' => self::FPI_HTML_4_FRAMESET,
        'html-401-strict' => self::FPI_HTML_4_01_STRICT,
        'html-401-transitional' => self::FPI_HTML_4_01_TRANSITIONAL,
        'html-401-frameset' => self::FPI_HTML_4_01_FRAMESET,
        'html+aria-401' => self::FPI_HTML_4_01_ARIA,
        'html+rdfa-401-1' => self::FPI_HTML_4_01_RDFA_1,
        'html+rdfa-401-11' => self::FPI_HTML_4_01_RDFA_1_1,
        'html+rdfalite-401-11' => self::FPI_HTML_4_01_RDFALITE_1_1,
        'html+iso15445-1' => self::FPI_HTML_ISO_15445,
        'html+iso15445-1-alternative' => self::FPI_HTML_ISO_15445_ALTERNATIVE,
        'xhtml-1-strict' => self::FPI_XHTML_1_STRICT,
        'xhtml-1-transitional' => self::FPI_XHTML_1_TRANSITIONAL,
        'xhtml-1-frameset' => self::FPI_XHTML_1_FRAMESET,
        'xhtml+basic-1' => self::FPI_XHTML_1_BASIC,
        'xhtml+print-1' => self::FPI_XHTML_1_PRINT,
        'xhtml+mobile-1' => self::FPI_XHTML_MOBILE_1,
        'xhtml+mobile-11' => self::FPI_XHTML_MOBILE_1_1,
        'xhtml+mobile-12' => self::FPI_XHTML_MOBILE_1_2,
        'xhtml-11' => self::FPI_XHTML_1_1,
        'xhtml+basic-11' => self::FPI_XHTML_1_1_BASIC,
        'xhtml+rdfa-1' => self::FPI_XHTML_RDFA_1,
        'xhtml+rdfa-11' => self::FPI_XHTML_RDFA_1_1,
        'xhtml+aria-1' => self::FPI_XHTML_ARIA_1
    ];

    /**
     * @var array
     */
    private $fpiToUriMap = [
        self::FPI_HTML_4_STRICT => [
            self::URI_HTML_4_STRICT,
            self::URI_HTML_4_01_STRICT,
        ],
        self::FPI_HTML_4_TRANSITIONAL => [
            self::URI_HTML_4_TRANSITIONAL,
            self::URI_HTML_4_01_TRANSITIONAL,
        ],
        self::FPI_HTML_4_FRAMESET => [
            self::URI_HTML_4_FRAMESET,
            self::URI_HTML_4_01_FRAMESET,
        ],
        self::FPI_HTML_4_01_STRICT => [
            self::URI_HTML_4_01_STRICT,
        ],
        self::FPI_HTML_4_01_TRANSITIONAL => [
            self::URI_HTML_4_01_TRANSITIONAL,
        ],
        self::FPI_HTML_4_01_FRAMESET => [
            self::URI_HTML_4_01_FRAMESET,
        ],
        self::FPI_HTML_4_01_ARIA => [
            self::URI_HTML_ARIA_4_01_1
        ],
        self::FPI_HTML_4_01_RDFA_1 => [
            self::URI_HTML_RDFA_4_01_1
        ],
        self::FPI_HTML_4_01_RDFA_1_1 => [
            self::URI_HTML_RDFA_4_01_1_1
        ],
        self::FPI_HTML_4_01_RDFALITE_1_1 => [
            self::URI_HTML_RDFALITE_4_01_1_1
        ],
        self::FPI_XHTML_1_STRICT => [
            self::URI_XHTML_1_STRICT,
        ],
        self::FPI_XHTML_1_TRANSITIONAL => [
            self::URI_XHTML_1_TRANSITIONAL,
        ],
        self::FPI_XHTML_1_FRAMESET => [
            self::URI_XHTML_1_FRAMESET,
        ],
        self::FPI_XHTML_1_BASIC => [
            self::URI_XHTML_BASIC_1,
        ],
        self::FPI_XHTML_1_PRINT => [
            self::URI_XHTML_PRINT_1,
        ],
        self::FPI_XHTML_MOBILE_1 => [
            self::URI_XHTML_MOBILE_1
        ],
        self::FPI_XHTML_MOBILE_1_1 => [
            self::URI_XHTML_MOBILE_1_1
        ],
        self::FPI_XHTML_MOBILE_1_2 => [
            self::URI_XHTML_MOBILE_1_2
        ],
        self::FPI_XHTML_1_1 => [
            self::URI_XHTML_1_1,
        ],
        self::FPI_XHTML_1_1_BASIC => [
            self::URI_XHTML_BASIC_1_1,
        ],
        self::FPI_XHTML_RDFA_1 => [
            self::URI_XHTML_RDFA_1
        ],
        self::FPI_XHTML_RDFA_1_1 => [
            self::URI_XHTML_RDFA_1_1
        ],
        self::FPI_XHTML_ARIA_1 => [
            self::URI_XHTML_ARIA_1,
        ],
    ];

    /**
     * @var string
     */
    private $mode = self::MODE_STRICT;

    /**
     * @param $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * Is the given doctype 100% valid?
     * Is valid if the FPI is known and the URI matches exactly
     *
     * @param HtmlDocumentType $documentType
     *
     * @return bool
     */
    public function isValid(HtmlDocumentType $documentType)
    {
        $fpi = $documentType->getFpi();
        $uri = $documentType->getUri();

        $hasFpi = null !== $fpi;
        $hasUri = null !== $uri;

        if (!$hasFpi && !$hasUri) {
            return true;
        }

        if (in_array($fpi, $this->fpiList) && !$hasUri) {
            return true;
        }

        if (!$hasFpi && self::DOCTYPE_HTML5_LEGACY_COMPAT_URI === $uri) {
            return true;
        }

        if (self::MODE_IGNORE_FPI_URI_VALIDITY === $this->mode) {
            return true;
        }

        $validUriSet = isset($this->fpiToUriMap[$fpi])
            ? $this->fpiToUriMap[$fpi]
            : [];

        return in_array($uri, $validUriSet);
    }
}
