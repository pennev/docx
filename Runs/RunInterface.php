<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 09:01.
 */

namespace Docx\Runs;

use Docx\Blocks\BlockInterface;

/**
 * Created by PhpStorm.
 * User: lmerotta
 * Date: 31.05.16
 * Time: 16:00.
 */
interface RunInterface
{
    /**
     * RunInterface constructor.
     * @param BlockInterface $block
     * @param \SimpleXMLElement $element
     */
    public function __construct(BlockInterface $block, \SimpleXMLElement $element);

    /**
     * @return string
     */
    public function render();
}
