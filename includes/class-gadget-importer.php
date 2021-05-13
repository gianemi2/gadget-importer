<?php 
class Gadget_Importer{
    function __construct(){
        $this->FTP_HOST = 'transfer.midoceanbrands.com';
        $this->FTP_USER = 'gadgetfirenze';
        $this->FTP_PASS = '80841212';
        $this->XML_NAME = 'products.xml';
    }

    function download_xml() {
        try {
            $ftp_conn = ftp_connect($this->FTP_HOST);
            $login = ftp_login($ftp_conn, $this->FTP_USER, $this->FTP_PASS);

            $local_file = GADGET_PATH . $this->XML_NAME;
            $server_file = 'prodinfo_it_v1.1.xml';
            if (ftp_get($ftp_conn, $local_file, $server_file, FTP_BINARY)) {
                echo "<br>Successfully downloaded ";
            } else {
                echo "<br>Error while downloading from ";
            }

        } catch (\Throwable $th) {
            echo $th;
        }

    }

    function read(){ 
        $streamer = Prewk\XmlStringStreamer::createStringWalkerParser(GADGET_PATH . $this->XML_NAME, ['captureDepth' => 3]);
        while ($node = $streamer->getNode()) {
            // $node will be a string like this: "<customer><firstName>Jane</firstName><lastName>Doe</lastName></customer>"
            $simpleXmlNode = simplexml_load_string($node);
            $this->import($simpleXmlNode);
        }
    }

    function import($xml_product){

    }

    function run(){
        //$this->download_xml();
        $this->read();
    }
}