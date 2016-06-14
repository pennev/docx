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
class TableColumn implements BlockInterface
{
	/**
	 * @var Document
	 */
	private $document;

	/**
	 * @var BlockInterface[]
	 */
	private $blocks = array();

	/**
	 * @var int
	 */
	private $colSpan = 0;

	/**
	 * @var int
	 */
	private $rowSpan = 0;

	/**
	 * @var bool
	 */
	private $vMerge = false;

	/**
	 * @var bool
	 */
	private $vMergeRestart = false;

	/**
	 * @inheritdoc
	 */
	public function __construct(Document $document, \SimpleXMLElement $element)
	{
		$this->document = $document;
		$properties = $element->children('w', true)->tcPr;

		if ($properties->children('w', true)->gridSpan) {
			$this->colSpan = (int) $properties->children('w', true)->gridSpan->attributes('w', true)->val;
		}

		if ($properties->children('w', true)->vMerge) {
			$this->vMerge = true;
			$this->vMergeRestart = ($properties->children('w', true)->vMerge->attributes('w', true)->val == 'restart');
		}

		foreach ($element->children('w', true) as $item) {
			switch ($item->getName()) {
				case 'p':
					$this->blocks[] = new Paragraph($this->document, $item);
					break;
				case 'tbl':
					$this->blocks[] = new Table($this->document, $item);
					break;
			}
		}
	}

	/**
	 * @return bool
	 */
	public function isVMerge()
	{
		return $this->vMerge;
	}

	/**
	 * @return bool
	 */
	public function isVMergeRestart()
	{
		return $this->vMergeRestart;
	}

	/**
	 * @param int $rowSpan
	 *
	 * @return TableColumn
	 */
	public function setRowSpan($rowSpan)
	{
		$this->rowSpan = $rowSpan + 1;

		return $this;
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
		foreach ($this->blocks as $paragraph) {
			$runs .= $paragraph->render($renderInlineStyles);
		}
		$args[] = $runs;

		return vsprintf($format, $args);
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
		throw new \BadMethodCallException('should not be called!');
	}
}
