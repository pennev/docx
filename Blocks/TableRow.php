<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 13:55
 */

namespace Docx\Blocks;

use Docx\Document;

class TableRow implements BlockInterface
{
    private $document;
    private $columns = array();

    /**
     * Table constructor.
     * @param Document $document
     * @param \SimpleXMLElement $element
     */
    public function __construct(Document $document, \SimpleXMLElement $element)
    {
        $this->document = $document;

        foreach ($element->children('w', true)->tc as $column) {
            $this->columns[] = new TableColumn($this->document, $column);
        }
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
        $format = '<tr>%s</tr>';

        $text = '';

        foreach ($this->columns as $column) {
            $text .= $column->render($renderInlineStyles);
        }

        return sprintf($format, $text);
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

    public function setRowsWithVmerge(array $vMergeIndexes)
    {
        foreach ($vMergeIndexes as $index => $amount) {
            $this->columns[$index]->setRowSpan($amount);
        }
    }

    public function prepareRowSpans()
    {
        foreach ($this->columns as $index => $column) {
            /** @var TableColumn $column */
            if ($column->isVMerge()) {
                yield $index;
            }
        }
    }

}