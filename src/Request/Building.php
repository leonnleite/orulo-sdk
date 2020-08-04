<?php


namespace LeonnLeite\Orulo\Request;


use LeonnLeite\Orulo\Client;

class Building extends \LeonnLeite\Orulo\Request
{
    protected static string $path = 'api/v2/buildings';

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
        $response = $this->client->getHttpClient()->sendRequest($request);
        if ($response->getStatusCode() !== 200) {
            throw new \InvalidArgumentException();
        }
        return json_decode($response->getBody()->getContents(), true);

    }

    public function find(int $id)
    {
        $this->client->getAuth()->connect();

        $url = Client::getBaseUri() . self::getPath() . '/' . $id;

        $request = $this->createGetRequest($url, $this->client);
        $response = $this->client->getHttpClient()->sendRequest($request);
        if ($response->getStatusCode() !== 200) {
            throw new \InvalidArgumentException();
        }
        return json_decode($response->getBody()->getContents(), true);
    }

    public function findByName(string $name, array $params = [])
    {
        $this->client->getAuth()->connect();

        $url = Client::getBaseUri() . self::getPath() . '/' . $name . '/search';
        $request = $this->createGetRequest($url, $this->client, $params);

        $response = $this->client->getHttpClient()->sendRequest($request);
        if ($response->getStatusCode() !== 200) {
            throw new \InvalidArgumentException();
        }
        return json_decode($response->getBody()->getContents(), true);
    }

}
