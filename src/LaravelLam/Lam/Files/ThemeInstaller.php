<?php namespace LaravelLam\Lam\Files;

use ZipArchive;

/**
 * User: nicolaslopezj
 * Date: 16-10-14
 * Time: 12:22
 */

class ThemeInstaller {

    protected $zip_path;

    protected $theme_name;

    /**
     * The path of the downloaded zip file
     * @param $zip_path
     */
    public function setZipPath($zip_path) {
        $this->zip_path = $zip_path;
    }

    /**
     * The name of the theme ej: nicolaslopezj/cool-theme
     * @param $theme_name
     */
    public function setThemeName($theme_name) {
        $this->theme_name = $theme_name;
    }

    /**
     * Installs the theme
     */
    public function install() {
        $this->unzipFile();
        $this->changeTemporalNameToRealName();
        $this->deleteFolder($this->getTempDestination());
    }

    /**
     * Deletes a folder recursively
     * @param $dir
     */
    protected function deleteFolder($dir) {
        $deleter = new FolderRemover;
        $deleter->setPath($dir);
        $deleter->remove();
    }

    /**
     * Returns the temporal destination
     * If it doesnt exists creates it
     * @return string
     */
    protected function getTempDestination() {
        $path = base_path() . '/lam/views/temp';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    /**
     * Returns the destination of the theme
     * Ej ../views/lam/nicolaslopezj/cool-theme
     * If it doesnt exists creates it
     * @return string
     */
    protected function getFinalDestination() {
        $parts = explode('/', $this->theme_name);
        $path = base_path() . '/lam/views/' . $parts[0];
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $path . '/' . $parts[1];
    }

    /**
     * Moves the temporal folder the to final folder
     */
    protected function changeTemporalNameToRealName() {
        $dir = opendir($this->getTempDestination());

        while (($file = readdir($dir)) !== false)
        {
            if ($file != '.' &&
                $file != '..' &&
                $file != '.DS_Store' &&
                $this->getTempDestination() . '/' . $file != $this->zip_path) {
                rename($this->getTempDestination() . '/' . $file, $this->getFinalDestination());
            }
        }

        closedir($dir);
    }

    /**
     * Extracts the zip to the temporal destination
     */
    protected function unzipFile() {
        $zip = new ZipArchive;
        if (true === $zip->open($this->zip_path)) {
            $zip->extractTo($this->getTempDestination() . '/');
            $zip->close();

        } else {
            // handle error
            echo 'error';
        }
    }

}