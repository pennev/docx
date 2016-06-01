<?php

require 'vendor/autoload.php';
$file = new \Docx\File(__DIR__.'/convertTest.docx');
$file->addStyle(new \Docx\TestStyle('Titre1', 'h1', 'test', array('color' => 'red')));
//$file->document->renderInlineStyles = true;

echo '<Doctype html><html><head><meta charset="utf-8"></head><body>';
echo $file->document->render();
echo '</body></html>';
