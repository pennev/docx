<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 13:55
 */

namespace Docx\Blocks;

use Docx\Document;

class Table implements BlockInterface
{
    private $document;
    private $numberOfColumns;
    private $rows = array();
    private $colSpans = array();

    /**
     * Table constructor.
     * @param Document $document
     * @param \SimpleXMLElement $element
     */
    public function __construct(Document $document, \SimpleXMLElement $element)
    {
        $this->document = $document;
        $this->numberOfColumns = count($element->children('w', true)->tblGrid->children('w', true)->gridCol);

        foreach ($element->children('w', true)->tr as $row) {
            $this->rows[] = new TableRow($this->getDocument(), $row);
        }

        foreach ($this->rows as $index => $row) {
            foreach ($row->prepareRowSpans() as $vMergeIndexes) {
                if (empty($this->colSpans[$index])) {
                    $this->colSpans[$index] = array();
                }

                if (empty($this->colSpans[$index][$vMergeIndexes])) {
                    $this->colSpans[$index][$vMergeIndexes] = 0;
                }

                $this->colSpans[$index][$vMergeIndexes]++;
            }
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
        $format = '<table>%s</table>';
        $text = '';

        foreach ($this->rows as $index => $row) {
            if (!empty($this->colSpans[$index])) {
                $row->setRowsWithVmerge($this->colSpans[$index]);
            }
            $text .= $row->render($renderInlineStyles);
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
        throw new \BadMethodCallException('should not be called !');
    }

}