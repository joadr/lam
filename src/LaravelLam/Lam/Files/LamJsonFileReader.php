<?php namespace LaravelLam\Lam\Files;
/**
 * User: nicolaslopezj & joadr
 * Date: 17-10-14
 * Time: 12:24
 */
class LamJsonFileReader {

    /**
     * Returns the content of the file
     * @return array
     */
    public function readFile() {
        $content = $this->getFileContents();
        return json_decode($content, 1);
    }

    /**
     * Returns the required themes
     * @return array
     */
    public function getRequire() {
        $content = $this->readFile();
        if (array_key_exists('require', $content)) {
            return $content['require'];
        }
        return [];
    }

    /**
     * Returns the bower dependencies
     * @return array
     */
    public function getBower() {
        $content = $this->readFile();
        if (array_key_exists('bower', $content)) {
            return $content['bower'];
        }
        return [];
    }

    /**
     * Returns the path of the file
     * @return string
     */
    public function getFilePath() {
        return base_path() . '/lam.json';
    }

    /**
     * Returns the content in string of the file
     * @return string
     */
    protected function getFileContents() {
        $path = $this->getFilePath();
        return file_get_contents($path);
    }

    /**
     * Creates json file with it's content
     */
    public function createJson(){
        $json = fopen($this->getFilePath(), 'w');
$content = "{
    \"require\" : {
    },
    \"bower\" : [
    ]
}";
        fwrite($json, $content);
        fclose($json);
    }

}