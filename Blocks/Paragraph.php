<?php

namespace Docx\Blocks;

use Docx\Document;
use Docx\Runs\HyperLink;
use Docx\Runs\Run;
use Docx\StyleInterface;

/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 31.05.16
 * Time: 15:52
 */
class Paragraph implements BlockInterface
{
    private $document;
    private $styleName = '';
    private $runs = array();

    public function __construct(Document $document, \SimpleXMLElement $element)
    {
        $this->document = $document;
        $this->styleName = (string)$element->children('w', true)->pPr
            ->children('w', true)->pStyle->attributes('w', true)->val;

        foreach ($element->xpath('w:r|w:hyperlink') as $run) {
            switch ($run->getName()) {
                case 'r':
                    $this->runs[] = new Run($this, $run);
                    break;
                case 'hyperlink':
                    $this->runs[] = new HyperLink($this, $run);
                    break;
            }
        }
    }

    public function render()
    {
        $format = '%s';

        if (!empty($this->styleName)) {
            if (!empty($this->getDocument()->getFile()->styles[$this->styleName])) {
                /** @var StyleInterface $style */
                $style = $this->getDocument()->getFile()->styles[$this->styleName];

                $format = '<%s';

                $args = array($style->getTag());

                if (!empty($style->getClass())) {
                    $format .= ' class="%s"';
                    $args[] = $style->getClass();
                }

                if (!empty($style->renderInlineStyles())) {
                    $format .= ' style="%s">';
                    $args[] = $style->renderInlineStyles();
                }

                $format .= '%s</%s>';
                $args[] = '%s';
                $args[] = $style->getTag();

                $format = vsprintf($format, $args);
            }
        }

        $return = '';
        foreach ($this->runs as $run) {
            $return .= $run->render();
        }

        return sprintf($format, $return);
    }

    /**
     * @return Document
     */
    public function getDocument()
    {
        return $this->document;
    }
}
