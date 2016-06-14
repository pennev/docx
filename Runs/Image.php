<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 10:46.
 */

namespace Docx\Runs;

use Docx\Document;
use Docx\Relation;


/**
 * Created by PhpStorm.
 * User: lmerotta
 * Date: 31.05.16
 * Time: 16:00.
 */
class Image
{
	/**
	 * @param \SimpleXMLElement $element
	 * @param Document $document
	 * @return string
	 */
	public static function render(\SimpleXMLElement $element, Document $document)
	{
		$format = '<img src="%s" />';

		$imageId = (string) $element
			->children('wp', true)->inline
			->children('a', true)->graphic
			->children('a', true)->graphicData
			->children('pic', true)->pic
			->children('pic', true)->blipFill
			->children('a', true)->blip
			->attributes('r', true)->embed;

		/** @var Relation $relation */
		$relation = $document->getFile()->relations[$imageId];

		return sprintf($format, $document->getFile()->images[$relation->getTarget()]);
	}
}
