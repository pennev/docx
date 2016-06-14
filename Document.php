<?php

namespace Docx;

use Docx\Blocks\BlockInterface;
use Docx\Blocks\Paragraph;
use Docx\Blocks\Table;

/**
 * Created by PhpStorm.
 * User: lmerotta
 * Date: 31.05.16
 * Time: 15:27.
 */
class Document
{
    /**
     * @var File
     */
    private $file;

    /**
     * @var \SimpleXMLElement[]
     */
    private $xml;

    /**
     * @var BlockInterface[]
     */
    private $childs = array();

    /**
     * @var bool
     */
    public $renderInlineStyles = false;

    /**
     * Document constructor.
     * @param File $file
     * @param $xmlString
     */
    public function __construct(File $file, $xmlString)
    {
        $this->file = $file;
        $this->xml = simplexml_load_string($xmlString);
        $this->xml = $this->xml->children('w', true)->body;

        foreach ($this->xml->children('w', true) as $child) {
            /** @var \SimpleXMLElement $child */
            switch ($child->getName()) {
                case 'p':
                    $this->childs[] = new Paragraph($this, $child);
                    break;
                case 'tbl':
                    $this->childs[] = new Table($this, $child);
                    break;
            }
        }
    }

    /**
     * @return string
     */
    public function render()
    {
        $listStarted = false;
        $listLevel = 0;
        $return = '';
        foreach ($this->childs as $child) {
            if (($child->isList() && !$listStarted)) {
                $listStarted = true;
                $return .= '<ul>';
            } elseif (!$child->isList() && $listStarted) {
                $listStarted = false;

                for ($listLevel; $listLevel > 0; --$listLevel) {
                    $return .= '</ul>';
                }

                $return .= '</ul>';
            } elseif ($child->isList() && $child->getListLevel() < $listLevel) {
                $return .= '</ul>';
                $listLevel = $child->getListLevel();
            } elseif ($child->isList() && $child->getListLevel() > $listLevel) {
                $return .= '<ul>';
                $listLevel = $child->getListLevel();
            }

            $return .= $child->render($this->renderInlineStyles);
        }

        return $return;
    }

    public static function getStyle(\SimpleXMLElement $element)
    {
        if ($properties->children('w', true)->pStyle) {
            return (string)$properties->children('w', true)->pStyle->attributes('w', true)->val;
        }

        return '';
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }
}
