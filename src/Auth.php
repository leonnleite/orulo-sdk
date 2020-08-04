<?php


namespace LeonnLeite\Orulo;


use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use LeonnLeite\Orulo\Auth\Exception\UnauthorizedAuthenticationException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class Auth
{
    protected static string $path = 'oauth/token';

    protected ?string $token = null;

    protected string $clientId;

    protected string $clientSecret;

    protected ?\DateTime $createdAt;

    protected ?\DateTime $expiresAt;

    protected ?string $scope;

    protected ?array $scopes;

    protected ?string $tokenType;

    protected string $grantType = 'client_credentials';

    public function __construct(string $clientId, string $clientSecret, ClientInterface $httpClient)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->httpClient = $httpClient;
    }

    public function isAuthenticated(): bool
    {
        return !!$this->token;
    }

    public static function getPath(): string
    {
        return self::$path;
    }

    public function connect()
    {
        if ($this->isAuthenticated()) {
            return;
        }
        $url = Client::getBaseUri() . self::getPath();

        $formParams = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => $this->getGrantType()
        ];

        $result = $this->authRequest($url, $formParams);
        $this->hydrateAuthData($result);
        if (!isset($result['access_token'])) {
            throw new UnauthorizedAuthenticationException();
        }
    }

    public function getGrantType(): string
    {
        return $this->grantType;
    }

    public function setGrantType(string $grantType): void
    {
        $this->grantType = $grantType;
    }

    protected function hydrateAuthData(array $data): void
    {
        $this->token = $data['access_token'];
        $this->tokenType = $data['token_type'];
        $this->createdAt = \DateTime::createFromFormat('U', $data['created_at']);
        $this->expiresAt = \DateTime::createFromFormat('U', ($data['created_at'] + $data['expires_in']));
        $this->scope = $data['scope'];
        $this->scopes = explode(' ', $this->scope);
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getExpiresAt(): ?\DateTime
    {
        return $this->expiresAt;
    }

    public function getScopes(): ?array
    {
        return $this->scopes;
    }

    public function getTokenType(): ?string
    {
        return $this->tokenType;
    }

    private function authRequest(string $url, array $formParams)
    {
        $request = new Request('POST', $url, [
            'Content-Type'=> 'application/x-www-form-urlencoded'
        ], http_build_query($formParams));

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new UnauthorizedAuthenticationException();
        }

        $result = $response->getBody()->getContents();
        return json_decode($result, true);
    }

    public function getTokenToHeader(): array
    {
        if (!$this->isAuthenticated()) {
            return [];
        }
        return ['Authorization' => $this->getTokenType() . ' ' . $this->getToken()];
    }


}
