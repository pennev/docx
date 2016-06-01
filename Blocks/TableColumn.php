<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 13:55
 */

namespace Docx\Blocks;

use Docx\Document;

class TableColumn implements BlockInterface
{
    private $document;
    private $paragraphs = array();
    private $colSpan = 0;
    private $rowSpan = 0;
    private $vMerge = false;
    private $vMergeRestart = false;

    /**
     * Table constructor.
     * @param Document $document
     * @param \SimpleXMLElement $element
     */
    public function __construct(Document $document, \SimpleXMLElement $element)
    {
        $this->document = $document;
        $properties = $element->children('w', true)->tcPr;

        if ($properties->children('w', true)->gridSpan) {
            $this->colSpan = (int)$properties->children('w', true)->gridSpan->attributes('w', true)->val;
        }

        if ($properties->children('w', true)->vMerge) {
            $this->vMerge = true;
            $this->vMergeRestart = ($properties->children('w', true)->vMerge->attributes('w', true)->val == 'restart');
        }

        foreach ($element->children('w', true)->p as $paragraph) {
            $this->paragraphs[] = new Paragraph($this->document, $paragraph);
        }
    }

    /**
     * @return boolean
     */
    public function isVMerge()
    {
        return $this->vMerge;
    }

    /**
     * @return boolean
     */
    public function isVMergeRestart()
    {
        return $this->vMergeRestart;
    }

    /**
     * @param int $rowSpan
     * @return TableColumn
     */
    public function setRowSpan($rowSpan)
    {
        $this->rowSpan = $rowSpan + 1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param $renderInlineStyles
     * @return mixed
     */
    public function render($renderInlineStyles)
    {
        if ($this->isVMerge() && !$this->isVMergeRestart()) {
            return '';
        }

        $format = '<td';
        $args = array();

        if ($this->colSpan > 0) {
            $format .= ' colspan="%d"';
            $args[] = $this->colSpan;
        }

        if ($this->rowSpan > 0) {
            $format .= ' rowspan="%d"';
            $args[] = $this->rowSpan;
        }

        $format .= '>%s</td>';

        $runs = '';
        foreach ($this->paragraphs as $paragraph) {
            $runs .= $paragraph->render($renderInlineStyles);
        }
        $args[] = $runs;

        return vsprintf($format, $args);
    }

    /**
     * @return mixed
     */
    public function isList()
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function getListLevel()
    {
        throw new \BadMethodCallException('should not be called!');
    }

}