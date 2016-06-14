<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 31.05.16
 * Time: 16:14.
 */

namespace Docx\Runs;

use Docx\Blocks\BlockInterface;

/**
 * Created by PhpStorm.
 * User: lmerotta
 * Date: 31.05.16
 * Time: 16:00.
 */
class Run implements RunInterface
{
    /**
     * @var BlockInterface
     */
    private $parentBlock;

    /**
     * @var bool
     */
    private $bold = false;

    /**
     * @var bool
     */
    private $italic = false;

    /**
     * @var bool
     */
    private $underline = false;

    /**
     * @var string
     */
    private $vertAlign = '';

    /**
     * @var string
     */
    private $format = '%s';

    /**
     * @var string
     */
    private $plainText = '';

    /**
     * @inheritdoc
     */
    public function __construct(BlockInterface $block, \SimpleXMLElement $element)
    {
        $this->parentBlock = $block;
        $this->parseStyles($element);

        foreach ($element->children('w', true) as $child) {
            switch ($child->getName()) {
                case 't':
                    $this->plainText .= (string) $child;
                    break;
                case 'drawing':
                    $this->plainText .= Image::render($child, $block->getDocument());
                    break;
            }
        }
    }

    /**
     * @param \SimpleXMLElement $element
     */
    private function parseStyles(\SimpleXMLElement $element)
    {
        $properties = $element->children('w', true)->rPr;

        if ($properties) {
            $this->setItalic($properties);
            $this->setUnderline($properties);
            $this->setBold($properties);
            $this->setVertAlign($properties);
        }
    }

    private function setItalic($properties)
    {
        if ((isset($properties->children('w', true)->i) ||
            isset($properties->children('w', true)->iCs))) {
            $this->format = '<i>'.$this->format.'</i>';
        }
    }

    private function setUnderline($properties)
    {
        if ((isset($properties->children('w', true)->u) ||
            isset($properties->children('w', true)->em))) {
            $this->format = '<em>'.$this->format.'</em>';
        }
    }

    private function setBold($properties)
    {
        if ((isset($properties->children('w', true)->b) ||
            isset($properties->children('w', true)->bCs))) {
            $this->format = '<b>'.$this->format.'</b>';
        }
    }

    private function setVertAlign($properties)
    {
        if ($properties->children('w', true)->vertAlign) {
            switch ((string) $properties->children('w', true)->vertAlign->attributes('w', true)->val) {
                case 'superscript':
                    $this->vertAlign = 'sup';
                    break;
                case 'subscript':
                    $this->vertAlign = 'sub';
                    break;
            }

            $this->format = '<'.$this->vertAlign.'>%s</'.$this->vertAlign.'>';
        }
    }

    /**
     * @inheritdoc
     */
    public function render()
    {
        return sprintf($this->format, $this->plainText);
    }
}
