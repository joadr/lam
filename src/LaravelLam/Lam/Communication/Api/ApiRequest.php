<?php namespace LaravelLam\Lam\Communication\Api;

use GuzzleHttp\Client;

/**
 * User: nicolaslopezj
 * Date: 16-10-14
 * Time: 12:19
 */

class ApiRequest {

    protected $client;
    protected $base_url = 'http://laravel-themes.app/api/v1/';

    public function __construct() {
        $this->client = new Client();
    }

    /**
     * Executes a get request
     * @param $method
     * @param $data
     * @return mixed
     */
    public function get($method, $data) {
        $response = $this->client->get($this->base_url . $method, ['query' => $data]);
        $json = $response->json();
        return $json;
    }

}