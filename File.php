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
    public $styles = array();
    public $relations = array();
    public $images = array();
    public $tmpDir;

    public function __construct($filename, $disableExternalEntities = true)
    {
        $this->tmpDir = __DIR__.'/tmp/';
        $doc = null;
        libxml_disable_entity_loader($disableExternalEntities) ;
        $this->filename = $filename;

        $zip = zip_open($this->filename);
        while ($zipEntry = zip_read($zip)) {
            $entryName = zip_entry_name($zipEntry);
            if (zip_entry_open($zip, $zipEntry) == false) {
                continue;
            }

            if ($entryName == 'word/_rels/document.xml.rels') {
                $relationXml = simplexml_load_string(zip_entry_read($zipEntry, zip_entry_filesize($zipEntry)));

                foreach ($relationXml->Relationship as $relationship) {
                    $relation = new Relation($relationship);
                    $this->relations[$relation->getRelId()] = $relation;
                }
            }

            # Get the document structure
            if ($entryName == 'word/document.xml') {
                $doc = zip_entry_read($zipEntry, zip_entry_filesize($zipEntry));
            }

            if (strpos($entryName, 'word/media/') !== false) {
                $mediaName = str_replace('word/', '', $entryName);
                $type = pathinfo($mediaName, PATHINFO_EXTENSION);

                $imageData = zip_entry_read($zipEntry, zip_entry_filesize($zipEntry));
                $this->images[$mediaName] = 'data:image/'.$type.';base64,'.base64_encode($imageData);
            }

            zip_entry_close($zipEntry);
        }
        zip_close($zip);
        $this->document = new Document($this, $doc);
    }
    
    public function addStyle(StyleInterface $style)
    {
        $this->styles[$style->getStyleName()] = $style;
    }
}
