<?php


namespace LeonnLeite\Orulo;



use LeonnLeite\Orulo\Request\Building;
use LeonnLeite\Orulo\Request\Partner;
use LeonnLeite\Orulo\Request\State;
use Monolog\Handler\StreamHandler;
use Psr\Http\Client\ClientInterface;


use Monolog\Logger;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;

class Client
{
    protected static string $baseUri = 'https://www.orulo.com.br/';

    protected Auth $auth;

    protected ClientInterface $httpClient;

    private State $state;

    private Building $building;

    private Partner $partner;

    public function __construct(string $clientId, string $clientSecret, ?ClientInterface $httpClient = null)
    {

        $stack = HandlerStack::create();


        $log = new Logger('logger');
        $log->pushHandler(new StreamHandler(__DIR__ . '/your.log', Logger::DEBUG));
        $stack->push(
            Middleware::log(
                $log,
                new MessageFormatter('{req_headers} {host} - {method} - {uri} - {req_body}  @ ')
            )
        );
        $this->httpClient = $httpClient ?? new \GuzzleHttp\Client([
                'handler' => $stack,
        ]);
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
    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }



}
