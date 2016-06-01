<?php

namespace Docx;
use Docx\Blocks\Paragraph;
use Docx\Blocks\Table;

/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 31.05.16
 * Time: 15:27
 */
class Document
{
    private $file;
    private $xml;
    private $childs = array();
    public $renderInlineStyles = false;

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


                for ($listLevel; $listLevel > 0; $listLevel--) {
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

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }
}
