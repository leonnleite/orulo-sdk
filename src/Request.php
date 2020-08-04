<?php


namespace LeonnLeite\Orulo;


use Psr\Http\Message\RequestInterface;

abstract class Request
{

    public function createGetRequest(string $url, Client $client, array $params = []): RequestInterface
    {
        return new \GuzzleHttp\Psr7\Request(
            'GET',
            $url,
            $client->getAuth()->getTokenToHeader() +
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            http_build_query($params)
        );
    }
}
