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
    private $xml;
    private $childs = array();

    public function __construct($xmlString)
    {
        $this->xml = simplexml_load_string($xmlString);
        $this->xml = $this->xml->children('w', true)->body;

        foreach ($this->xml->children('w', true) as $child) {
            /** @var \SimpleXMLElement $child */
            switch ($child->getName()) {
                case 'p':
                    $this->childs[] = new Paragraph($child);
                    break;
            }
        }
    }
}
