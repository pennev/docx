<?php

namespace Docx;

/**
 * Created by PhpStorm.
 * User: luciomerotta
 * Date: 31.05.16
 * Time: 15:27
 */
class File
{
    public $filename = '';
    public $document;
    public $styles;

    public function __construct($filename, $disableExternalEntities = true)
    {
        libxml_disable_entity_loader($disableExternalEntities) ;
        $this->filename = $filename;

        $zip = zip_open($this->filename);
        while ($zipEntry = zip_read($zip)) {
            $entryName = zip_entry_name($zipEntry);
            if (zip_entry_open($zip, $zipEntry) == false) {
                continue;
            }

            // TODO: load styles

            # Get the document structure
            if ($entryName == 'word/document.xml') {
                $this->document = new Document(zip_entry_read($zipEntry, zip_entry_filesize($zipEntry)));
            }

            zip_entry_close($zipEntry);
        }
        zip_close($zip);
    }
}
