<?php

namespace App\Service\WikiAPI;
use GuzzleHttp\Client;

class WikiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://ru.wikipedia.org/w/api.php',
        ]);
    }

    public function getPageContent($pageTitle){
        $response = $this->client->get('', [
            'query' => [
                'action' => 'query',
                'format' => 'json',
                'prop' => 'revisions',
                'titles' => $pageTitle,
                'rvprop' => 'size|content',
                'rvslots' => 'main',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
