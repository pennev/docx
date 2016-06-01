<?php
/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 01.06.16
 * Time: 09:21
 */

namespace Docx\Blocks;

use Docx\Document;

interface BlockInterface
{
    public function __construct(Document $document, \SimpleXMLElement $element);
    public function getDocument();
    public function render();
}
