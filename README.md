# Docx Parser

##### NOTE

This refactoring branch is a **WORK IN PROGRESS**. It is stable but surely not bug free. Feel free to contribute !

If you want to use the **legacy** version, please use the `dev-master branch`. It will be less frequently updated, and will only contain bugfixes.

#### What is supported

* Subscript/superscript
* Italic
* Underline
* Bold
* Adding Word Styles -> HTML conversion data
* Complex tables (embedded tables, vertically / horizontally merged cells, complex layouts)
* Unordered Lists
* Images

#### What is NOT supported

* Using Word's styles.xml file to generate HTML markup
* Ordered Lists

#### What WILL BE supported

* Footnotes
* ...

## Requirements

* PHP 5.3 or higher

## Documentation

For the install guide and reference, see the [Documentation](/doc/index.md)

## Contributing

Pull requests are welcome !
Unit and/or functional tests do not exist for now... Feel free to add them !