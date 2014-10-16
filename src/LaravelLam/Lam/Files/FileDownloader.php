<?php namespace LaravelLam\Lam\Files; 
/**
 * User: nicolaslopezj
 * Date: 16-10-14
 * Time: 12:21
 */
class FileDownloader {

    /**
     * Returns the destination of the file
     * If the directory doesnt exists creates it
     * @return string
     */
    public function getPath() {
        $this->path = app_path() . '/views/lam/temp/';
        if (!file_exists($this->path)) {
            mkdir($this->path, 0777, true);
        }
        return $this->path;
    }

    /**
     * @param $url The url of the file to download
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @param $filename The final name of the new file
     */
    public function setFilename($filename) {
        $this->filename = $filename;
    }

    /**
     * Downloads the file
     * @return string The name of the file
     */
    public function download(){
        $file = $this->getPath() . $this->filename;
        file_put_contents($file, fopen($this->url, 'r'));
        return $file;
    }


}