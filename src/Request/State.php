<?php


namespace LeonnLeite\Orulo\Request;


use GuzzleHttp\Psr7\Request;
use LeonnLeite\Orulo\Client;

class State extends \LeonnLeite\Orulo\Request
{
    protected static string $path = 'api/v2/addresses/states';

    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    private static function getPath()
    {
        return self::$path;
    }

    public function findAll(array $params = [])
    {
        $this->client->getAuth()->connect();

        $url = Client::getBaseUri() . self::getPath();

        $request = $this->createGetRequest($url, $this->client, $params);
        $response = $this->client->getHttpClient()->send($request);
        if ($response->getStatusCode() !== 200) {
            throw new \InvalidArgumentException();
        }
        return json_decode($response->getBody()->getContents(), true);
    }


}
