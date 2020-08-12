<?php


namespace LeonnLeite\Orulo\Request;


use LeonnLeite\Orulo\Client;

class BuildingFile extends \LeonnLeite\Orulo\Request
{
    protected static string $path = 'api/v2/buildings/__BUILDINGID__/files/';

    protected Client $client;


    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    private static function getPath()
    {
        return self::$path;
    }

    public function find(int $buildingId, int $fileId, array $params = [])
    {
        $path = str_replace('__BUILDINGID__', $buildingId, self::getPath()) . $fileId;

        $this->client->getAuth()->connect();

        $url = Client::getBaseUri() . $path;

        $request = $this->createGetRequest($url, $this->client, $params);
        $response = $this->client->getHttpClient()->send($request);
        if ($response->getStatusCode() !== 200) {
            throw new \InvalidArgumentException();
        }
        return json_decode($response->getBody()->getContents(), true);

    }

}
