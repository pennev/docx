<?php

require_once __DIR__ . '/vendor/autoload.php';

use Docx\Style;

$style = new Style("test");

die(var_dump($style));