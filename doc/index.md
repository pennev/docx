# Documentation

## Installation

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

.. code-block:: bash

    $ composer require lmerotta/docx >= 0.1

This command requires you to have Composer installed globally, as explained
in the `installation chapter` of the Composer documentation.

## Basic Usage

To convert a .docx file to HTML :

.. code-block:: php

    <?php
        use Docx\File;
        
        $file = new File($path_to_your_docx_file);
        echo $file->document->render(); // will output the generated HTML

## Adding Styles

This library supports Word Style to HTML conversion. To do so, you simply have to tell the `File` which Word Style has to be converted in what.

First, you need to create a class that implements `Docx\StyleInterface`. For the purpose of this example, let's use the `Docx\TestStyle` provided in this repository.

**Note**: If you are lazy you can use it, too.

.. code-block:: php

    <?php
        use Docx\File;
        use Docx\TestStyle;
        
        $file = new File($path_to_your_docx_file);
        
        $title1Style = new TestStyle('Title1', 'h1', 'class_to_add_to_h1', array('color' => 'red', 'margin' => '0 15px 0 0'));
        
        $file->addStyle($title1Style);
        
        echo $file->document->render(); // Every block with a "Title 1" style set in the .docx document will be converted to a <h1 class="class_to_add_to_h1" style="color: red; margin: 0 15px 0 0;">...</h1> element
        
        
**Q:** What if I don't want to output the inline CSS ?
**A:** You can simply disable it by doing `$file->document->renderInlineStyles = false;`