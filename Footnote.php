<?php

namespace Docx;
use Docx\Blocks\Paragraph;

/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 15.06.16
 * Time: 14:04
 */
class Footnote
{
    private $id;
    private $text = '';

    public function __construct(Document $document, \SimpleXMLElement $element)
    {
        $this->id = (int)$element->attributes('w', true)->id;

        foreach ($element->xpath('.//w:t') as $child) {
            /** @var \SimpleXMLElement $child */
            $this->text .= (string) $child;
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function isEmpty()
    {
        return empty($this->text);
    }

    public function render()
    {
        return $this->text;
    }
}