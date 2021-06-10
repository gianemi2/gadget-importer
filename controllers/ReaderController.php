<?php
class ReaderController{
    function __construct($xml_file, $nodeParseDepth = 3){
        $this->xml_file = $xml_file;
        $this->xml = XML_PATH . $xml_file['name'];
        $this->output = OUTPUT_PATH  . $xml_file['name'];
        $this->data = Prewk\XmlStringStreamer::createStringWalkerParser($this->xml, ['captureDepth' => $nodeParseDepth]);
    }
}