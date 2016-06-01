<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 09:28
 */

namespace Docx;

interface StyleInterface
{
    public function getTag();
    public function getClass();
    public function getStyleName();
    public function renderInlineStyles();
}
