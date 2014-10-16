<?php namespace LaravelLam\Lam\Communication\Api; 
/**
 * User: nicolaslopezj
 * Date: 16-10-14
 * Time: 12:19
 */
class ListOfThemesToDownloadRequest {

    protected $api;

    public function __construct() {
        $this->api = new ApiRequest;
    }

    /**
     * Returns the list of themes to download.
     * The web service compares with the versions that we send
     * @param $check list of themes to check
     * @return mixed
     */
    public function run($check) {
        $response = $this->api->get('themes/check', $check);
        return $response;
    }

}