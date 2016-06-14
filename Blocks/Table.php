<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 13:55.
 */

namespace Docx\Blocks;

use Docx\Document;

/**
 * Created by PhpStorm.
 * User: lmerotta
 * Date: 31.05.16
 * Time: 16:00.
 */
class Table implements BlockInterface
{
    /**
     * @var Document
     */
    private $document;

    /**
     * @var int
     */
    private $numberOfColumns;

    /**
     * @var TableRow[]
     */
    private $rows = array();

    /**
     * @var int[]
     */
    private $colSpans = array();

    /**
     * @inheritdoc
     */
    public function __construct(Document $document, \SimpleXMLElement $element)
    {
        $this->document = $document;
        $this->numberOfColumns = count($element->children('w', true)->tblGrid->children('w', true)->gridCol);

        foreach ($element->children('w', true)->tr as $index => $row) {
            $this->rows[] = new TableRow($this->getDocument(), $row);
            $this->colSpans[$index] = array();
        }

        foreach ($this->rows as $index => $row) {
            foreach ($row->prepareRowSpans() as $vMergeIndexes) {
                if (empty($this->colSpans[$index][$vMergeIndexes])) {
                    $this->colSpans[$index][$vMergeIndexes] = 0;
                }

                ++$this->colSpans[$index][$vMergeIndexes];
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function isList()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getListLevel()
    {
        throw new \BadMethodCallException('should not be called !');
    }
}
