<?php
class DownloadController{
    function __construct(){
        $this->FTP_HOST = 'transfer.midoceanbrands.com';
        $this->FTP_USER = 'gadgetfirenze';
        $this->FTP_PASS = '80841212';
        $this->FTP_CONNECTION = ftp_connect($this->FTP_HOST);
        ftp_login($this->FTP_CONNECTION, $this->FTP_USER, $this->FTP_PASS);
    }

    public function download_xml($xml) {
        try {
            $xml_filename = $xml['name'];
            $server_file = isset($xml['remote_folder']) ? $xml['remote_folder'] . $xml['name'] : $xml['name'];
            $local_file = XML_PATH . $xml_filename;
            if($this->isFileUpdated($local_file)){
                echo "<br>File already downloaded";
                return;
            }
            
            if (ftp_get($this->FTP_CONNECTION, $local_file, $server_file, FTP_BINARY)) {
                echo "<br>Successfully downloaded ";
            } else {
                echo "<br>Error while downloading from ";
            }

        } catch (\Throwable $th) {
            echo $th;
        }
    }

    function isFileUpdated($local_file){
        if(file_exists($local_file)){
            $last_edit = filemtime(GADGET_PATH . $xml_filename);
            $fiveDaysAgo = strtotime("-5 days");

            if($last_edit > $fiveDaysAgo){
                return true;   
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}