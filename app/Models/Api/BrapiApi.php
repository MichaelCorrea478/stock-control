<?php

namespace App\Models\Api;

use App\Exceptions\BrapiApiException;
use GuzzleHttp\Client;

class BrapiApi
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('stock.brapi.base_url');
    }

    public function get(string $endpoint, array $arguments = [])
    {
        try {
            $url = $this->baseUrl . $endpoint . '?' . http_build_query($arguments);
            $response = (new Client())->get($url);

            if ($response->getStatusCode() != 200) {
                throw new BrapiApiException('Houve um erro ao tentar fazer uma requisição para a api Brapi',
                                            $response->getStatusCode(),
                                            [
                                                'url' => $url,
                                                'status_code' => $response->getStatusCode(),
                                                'response' => $response->getBody()
                                            ]);
            }

            return json_decode($response->getBody());
        } catch (\Exception $e) {
            return null;
        }
    }
}
