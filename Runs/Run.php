<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 31.05.16
 * Time: 16:14
 */

namespace Docx\Runs;

use Docx\Blocks\BlockInterface;

class Run implements RunInterface
{
    private $parentBlock;
    private $bold = false;
    private $italic = false;
    private $underline = false;
    private $vertAlign = '';
    private $format = '%s';
    private $plainText = '';

    public function __construct(BlockInterface $block, \SimpleXMLElement $element)
    {
        $this->parentBlock = $block;
        $this->parseStyles($element);

        foreach ($element->children('w', true) as $child) {
            switch ($child->getName()) {
                case 't':
                    $this->plainText .= (string)$child;
                    break;
                case 'drawing':
                    $this->plainText .= Image::render($child, $block->getDocument());
                    break;
            }
        }
    }

    private function parseStyles(\SimpleXMLElement $element)
    {
        $properties = $element->children('w', true)->rPr;

        if ($properties) {
            $this->italic = (isset($properties->children('w', true)->i) ||
                isset($properties->children('w', true)->iCs));

            $this->bold = (isset($properties->children('w', true)->b) ||
                isset($properties->children('w', true)->bCs));

            $this->underline = (isset($properties->children('w', true)->u) ||
                isset($properties->children('w', true)->em));

            $vertAlign = $properties->children('w', true)->vertAlign;

            if ($vertAlign) {
                switch ((string)$vertAlign->attributes('w', true)->val) {
                    case 'superscript':
                        $this->vertAlign = 'sup';
                        break;
                    case 'subscript':
                        $this->vertAlign = 'sub';
                        break;
                }
            }
        }

        if ($this->italic) {
            $this->format = '<i>'.$this->format.'</i>';
        }

        if ($this->bold) {
            $this->format = '<b>'.$this->format.'</b>';
        }

        if ($this->underline) {
            $this->format = '<em>'.$this->format.'</em>';
        }

        if (!empty($this->vertAlign)) {
            $this->format = '<'.$this->vertAlign.'>%s</'.$this->vertAlign.'>';
        }
    }

    public function render()
    {
        return sprintf($this->format, $this->plainText);
    }
}