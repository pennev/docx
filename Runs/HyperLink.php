<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 31.05.16
 * Time: 16:14
 */

namespace Docx\Runs;

use Docx\Blocks\BlockInterface;
use Docx\Relation;

class HyperLink implements RunInterface
{
    private $parentBlock;
    /**
     * @var Relation
     */
    private $relation;
    private $runs = array();
    private $format = '';

    public function __construct(BlockInterface $block, \SimpleXMLElement $element)
    {
        $this->parentBlock = $block;
        foreach ($element->xpath('w:r') as $run) {
            $this->runs[] = new Run($block, $run);
        }

        $this->relation = $this
            ->parentBlock
            ->getDocument()
            ->getFile()
            ->relations[(string)$element->attributes('r', true)->id]
        ;
    }

    public function render()
    {
        if ($this->relation->getTargetMode() === 'External') {
            $this->format = '<a href="%s" target="_blank">%s</a>';
        } else {
            $this->format = '<a href="%s">%s</a>';
        }

        $toReturn = '';
        foreach ($this->runs as $run) {
            $toReturn .= $run->render();
        }

        return sprintf($this->format, $this->relation->getTarget(), $toReturn);
    }
}
