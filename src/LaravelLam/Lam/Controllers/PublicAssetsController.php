<?php namespace LaravelLam\Lam\Controllers;

use Controller;
use Response;
use File;

/**
 * User: nicolaslopezj
 * Date: 16-10-14
 * Time: 12:20
 */
class PublicAssetsController {

    /**
     * Returns a file in the public directory of a theme
     * @param $creator
     * @param $name
     * @param $path1
     * @param null $path2
     * @param null $path3
     * @param null $path4
     * @param null $path5
     * @param null $path6
     * @param null $path7
     * @return mixed
     */
    public function show($creator, $name, $path1, $path2 = null, $path3 = null, $path4 = null, $path5 = null, $path6 = null, $path7 = null) {

        // Get te path of the file
        $path = app_path() . '/views/lam/' . $creator . '/' . $name . '/public/' . $path1;
        $path .= $path2 ? '/' . $path2 : '';
        $path .= $path3 ? '/' . $path3 : '';
        $path .= $path4 ? '/' . $path4 : '';
        $path .= $path5 ? '/' . $path5 : '';
        $path .= $path6 ? '/' . $path6 : '';
        $path .= $path7 ? '/' . $path7 : '';

        // Initialize an instance of Symfony's File class.
        // This is a dependency of Laravel so it is readily available.
        $file = new \Symfony\Component\HttpFoundation\File\File($path);

        // Make a new response out of the contents of the file
        // Set the response status code to 200 OK
        $response = Response::make(
            File::get($path),
            200
        );

        // Modify our output's header.
        // Set the content type to the mime of the file.
        // In the case of a .jpeg this would be image/jpeg
        $response->header(
            'Content-type',
            $file->getMimeType()
        );

        // We return our image here.
        return $response;

    }

}