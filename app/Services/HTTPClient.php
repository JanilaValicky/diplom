<?php

namespace App;

use GuzzleHttp\Client;

class HTTPClient
{
    protected $client;

    protected $baseUrl;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        $this->client = new Client([
            'base_uri' => $baseUrl,
            'verify' => false,
        ]);
    }

    public function sendRequest($method, $endpoint, $options = [])
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        $response = $this->client->request($method, $url, $options);

        return json_decode($response->getBody()->getContents());
    }
}
