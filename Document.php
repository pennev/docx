<?php

namespace Docx;
use Docx\Blocks\Paragraph;

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
            }
        }
    }

    public function render()
    {
        $return = '';
        foreach ($this->childs as $child) {
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
