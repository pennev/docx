<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 09:28.
 */

namespace Docx;
namespace Docx;

/**
 * Created by PhpStorm.
 * User: lmerotta
 * Date: 31.05.16
 * Time: 15:40.
 */
interface StyleInterface
{
    /**
     * @return string
     */
    public function getTag();

    /**
     * @return string
     */
    public function getClass();

    /**
     * @return string
     */
    public function getStyleName();

    /**
     * @return string
     */
    public function renderInlineStyles();
}
