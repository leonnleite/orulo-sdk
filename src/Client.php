<?php


namespace LeonnLeite\Orulo;



use LeonnLeite\Orulo\Request\Building;
use LeonnLeite\Orulo\Request\Partner;
use LeonnLeite\Orulo\Request\State;
use Psr\Http\Client\ClientInterface;


use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;

class Client
{
    protected static string $baseUri = 'https://www.orulo.com.br/';

    protected Auth $auth;

    protected \GuzzleHttp\Client $httpClient;

    private State $state;

    private Building $building;

    private Partner $partner;

    public function __construct(string $clientId, string $clientSecret, ?\GuzzleHttp\Client $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new \GuzzleHttp\Client();
        $this->auth = new Auth($clientId, $clientSecret, $this->httpClient);

        $this->state = new State($this);
        $this->building = new Building($this);
        $this->partner = new Partner($this);
    }

    public function getAuth()
    {
        return $this->auth;
    }

    public static function getBaseUri(): string
    {
        return self::$baseUri;
    }

    public static function setBaseUri(string $baseUri): void
    {
        self::$baseUri = $baseUri;
    }

    public function getState(): State
    {
        return $this->state;
    }

    public function getBuilding(): Building
    {
        return $this->building;
    }

    public function getPartner(): Partner
    {
        return $this->partner;
    }

    /**
     * @return ClientInterface|\GuzzleHttp\Client
     */
    public function getHttpClient(): \GuzzleHttp\Client
    {
        return $this->httpClient;
    }


}
