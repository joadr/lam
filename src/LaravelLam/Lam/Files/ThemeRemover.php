<?php namespace LaravelLam\Lam\Files; 
/**
 * User: nicolaslopezj
 * Date: 16-10-14
 * Time: 12:24
 */
class ThemeRemover {

    /**
     * @param $theme_name The name of the theme
     */
    public function setThemeName($theme_name) {
        $this->theme_name = $theme_name;
    }

    /**
     * Returns the path of the theme to remove
     * @return string
     */
    protected function getDir() {
        return base_path() . '/lam/views/' . $this->theme_name;
    }

    /**
     * Removes the theme
     */
    public function remove() {
        $this->deleteFolder($this->getDir());
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

}