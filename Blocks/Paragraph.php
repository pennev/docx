<?php

namespace Docx\Blocks;

/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 31.05.16
 * Time: 15:52
 */
class Paragraph
{
    private $styleName = '';
    private $runs = array();

    public function __construct(\SimpleXMLElement $element)
    {
        $this->styleName = (string)$element->children('w', true)->pPr
            ->children('w', true)->pStyle->attributes('w', true)->val;

        foreach ($element->xpath('w:r') as $run) {
            $this->runs[] = new Run($run);
        }

        foreach ($this->runs as $r) {
            echo $r->render();
        }
    }
}
