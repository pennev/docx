<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 09:01
 */

namespace Docx\Runs;

use Docx\Blocks\BlockInterface;

interface RunInterface
{
    public function __construct(BlockInterface $block, \SimpleXMLElement $element);
    public function render();
}