<?php


namespace LeonnLeite\Orulo\Request;


use LeonnLeite\Orulo\Client;

class BuildingImage extends \LeonnLeite\Orulo\Request
{
    protected static string $path = 'api/v2/buildings/__BUILDINGID__/images';

    protected Client $client;


    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    private static function getPath()
    {
        return self::$path;
    }

    public function findAll(int $buildingId)
    {
        $this->client->getAuth()->connect();

        $path = str_replace('__BUILDINGID__', $buildingId, self::getPath());
        $url = Client::getBaseUri() . $path;

        $params = 'dimensions[]=200x140&dimensions[]=520x280&dimensions[]=1024x1024';
        $request = new \GuzzleHttp\Psr7\Request(
            'GET',
            $url,
            $this->client->getAuth()->getTokenToHeader() +
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            $params
        );

        $response = $this->client->getHttpClient()->send($request);
        if ($response->getStatusCode() !== 200) {
            throw new \InvalidArgumentException();
        }
        return json_decode($response->getBody()->getContents(), true);

    }

}
