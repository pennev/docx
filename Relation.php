<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 09:10.
 */

namespace Docx;

/**
 * Created by PhpStorm.
 * User: lmerotta
 * Date: 31.05.16
 * Time: 15:40.
 */
class Relation
{
    /**
     * @var string
     */
    private $relId;

    /**
     * @var string
     */
    private $target;

    /**
     * @var string
     */
    private $targetMode = '';

    /**
     * Relation constructor.
     * @param \SimpleXMLElement $element
     */
    public function __construct(\SimpleXMLElement $element)
    {
        $this->relId = (string) $element->attributes()->Id;
        $this->target = (string) $element->attributes()->Target;
        $this->targetMode = (string) $element->attributes()->TargetMode;
    }

    /**
     * @return string
     */
    public function getRelId()
    {
        return $this->relId;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return string
     */
    public function getTargetMode()
    {
        return $this->targetMode;
    }
}
