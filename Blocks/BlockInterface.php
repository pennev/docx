<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 09:21.
 */

namespace Docx\Blocks;

use Docx\Document;

/**
 * Created by PhpStorm.
 * User: lmerotta
 * Date: 31.05.16
 * Time: 15:40.
 */
interface BlockInterface
{
	/**
	 * BlockInterface constructor.
	 * @param Document $document
	 * @param \SimpleXMLElement $element
	 */
	public function __construct(Document $document, \SimpleXMLElement $element);

	/**
	 * @return Document
	 */
	public function getDocument();

	/**
	 * @param $renderInlineStyles
	 * @return string
	 */
	public function render($renderInlineStyles);

	/**
	 * @return bool
	 */
	public function isList();

	/**
	 * @return int
	 */
	public function getListLevel();
}
