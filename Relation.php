<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 09:10
 */

namespace Docx;

class Relation
{
    private $relId;
    private $target;
    private $targetMode = '';

    public function __construct(\SimpleXMLElement $element)
    {
        $this->relId = (string)$element->attributes()->Id;
        $this->target = (string)$element->attributes()->Target;
        $this->targetMode = (string)$element->attributes()->TargetMode;
    }

    /**
     * @return mixed
     */
    public function getRelId()
    {
        return $this->relId;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return mixed
     */
    public function getTargetMode()
    {
        return $this->targetMode;
    }

}
