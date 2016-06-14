<?php

namespace Docx\Blocks;

use Docx\Document;
use Docx\Runs\HyperLink;
use Docx\Runs\Run;
use Docx\Runs\RunInterface;
use Docx\StyleInterface;

/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 31.05.16
 * Time: 15:52.
 */
class Paragraph implements BlockInterface
{
    /**
     * @var Document
     */
    private $document;

    /**
     * @var string
     */
    private $styleName = '';

    /**
     * @var RunInterface[]
     */
    private $runs = array();

    /**
     * @var bool
     */
    private $list = false;

    /**
     * @var int
     */
    private $listLevel = 0;

    /**
     * @inheritdoc
     */
    public function __construct(Document $document, \SimpleXMLElement $element)
    {
        $this->document = $document;
        $this->determineProperties($element);

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
    
    private function determineProperties(\SimpleXMLElement $element)
    {
        $properties = $element->children('w', true)->pPr;

        if ($properties) {
            $this->styleName = Document::getStyle($properties);

            $numProperties = $properties->children('w', true)->numPr;

            if ($numProperties) {
                $this->listLevel = (isset($numProperties->children('w', true)->ilvl)
                    && isset($numProperties->children('w', true)->ilvl->attributes('w', true)->val) ?
                    (int) $numProperties->children('w', true)->ilvl->attributes('w', true)->val : -1);

            }
        }
    }

    /**
     * @inheritdoc
     */
    public function isList()
    {
        return $this->listLevel != -1;
    }

    /**
     * @inheritdoc
     */
    public function getListLevel()
    {
        return $this->listLevel;
    }

    /**
     * @inheritdoc
     */
    public function render($renderInlineStyles)
    {
        $defaultTag = ($this->isList() ? 'li' : 'p');
        $format = '%s';

        if (!empty($this->styleName)) {
            if (!empty($this->getDocument()->getFile()->styles[$this->styleName])) {
                /** @var StyleInterface $style */
                $style = $this->getDocument()->getFile()->styles[$this->styleName];

                $format = '<%s';

                if ($this->isList()) {
                    $args = array('li');
                } else {
                    $args = array($style->getTag());
                }

                if (!empty($style->getClass())) {
                    $format .= ' class="%s"';
                    $args[] = $style->getClass();
                }

                if (!empty($style->renderInlineStyles()) && $renderInlineStyles) {
                    $format .= ' style="%s"';
                    $args[] = $style->renderInlineStyles();
                }

                $format .= '>%s</%s>';
                $args[] = '%s';
                $args[] = $style->getTag();

                $format = vsprintf($format, $args);
            } else {
                $format = '<'.$defaultTag.' data-wordstyle="'.$this->styleName.'">%s</'.$defaultTag.'>';
            }
        } else {
            $format = '<'.$defaultTag.'>%s</'.$defaultTag.'>';
        }

        $return = '';
        foreach ($this->runs as $run) {
            $return .= $run->render();
        }

        return sprintf($format, $return);
    }

    /**
     * @inheritdoc
     */
    public function getDocument()
    {
        return $this->document;
    }
}
