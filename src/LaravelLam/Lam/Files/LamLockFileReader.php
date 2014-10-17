<?php namespace LaravelLam\Lam\Files;
/**
 * User: nicolaslopezj & joadr
 * Date: 16-10-14
 * Time: 12:26
 */
class LamLockFileReader {

    /**
     * Returns the parsed file
     * @return array
     */
    public function readFile() {
        $content = $this->getFileContents();
        return json_decode($content, 1);
    }

    /**
     * Returns the installed themes
     * @return array
     */
    public function getInstalled() {
        $content = $this->readFile();
        return $content['packages'];
    }

    /**
     * Returns the installed bower dependencies
     * @return array
     */
    public function getBower() {
        $content = $this->readFile();
        return $content['bower'];
    }

    /**
     * Saves the installed bower dependencies
     * @param $bower
     * @return array
     */
    public function setBower($bower) {
        $content = $this->readFile();
        $content['bower'] = $bower;
        $this->updateFile($content);
    }

    /**
     * Saves the installed themes dependencies
     * @param $themes
     * @return array
     */
    public function setInstalled($themes) {
        $content = $this->readFile();
        $content['packages'] = $themes;
        $this->updateFile($content);
    }

    /**
     * Returns the path of the file
     * @return string
     */
    public function getFilePath() {
        return base_path() . '/lam.lock';
    }

    /**
     * Returns the contents of the file in string
     * @return string
     */
    protected function getFileContents() {
        $path = $this->getFilePath();
        return file_get_contents($path);
    }

    protected function updateFile($content) {
        $content = json_encode($content);
        $file = fopen($this->getFilePath(), "w");
        fwrite($file, $content);
        fclose($file);
    }

    /**
     * Creates Lam's lock file
     */
    public function createLock(){
        $lock = fopen($this->getFilePath(), 'w');
        $content = '{ "packages": {}, "bower": [] }';
        fwrite($lock, $content);
        fclose($lock);
    }

}