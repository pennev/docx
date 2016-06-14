<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 09:58.
 */

namespace Docx;

class TestStyle implements StyleInterface
{
	private $styleName;
	private $tag;
	private $class;
	private $inlineStyles;

	public function __construct($styleName, $tag, $class = '', array $inlineStyles = array())
	{
		$this->styleName = $styleName;
		$this->tag = $tag;
		$this->class = $class;
		$this->inlineStyles = $inlineStyles;
	}

	public function getTag()
	{
		return $this->tag;
	}

	public function getClass()
	{
		return $this->class;
	}

	public function getStyleName()
	{
		return $this->styleName;
	}

	public function renderInlineStyles()
	{
		$return = '';

		foreach ($this->inlineStyles as $property => $value) {
			$return .= $property . ': ' . $value . ';';
		}

		return $return;
	}
}
