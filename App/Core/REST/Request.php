<?php

namespace App\Asmvc\Core\REST;

use GuzzleHttp\Client;

class Request
{
    private string $url;
    private bool $isAsync = false;
    private Client $client;
    private ?array $auth = null;

    public function __construct(array $config = [])
    {
        $this->client = new Client(array_merge([
            'headers' => [
                'Accept' => 'application/json'
            ]
        ], $config));
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function makeItAsync(): self
    {
        $this->isAsync = true;
        return $this;
    }

    public function disableAsync(): self
    {
        $this->isAsync = false;
        return $this;
    }

    private function configBuilder(array $additionalConfig = [])
    {
        $config = [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];

        if ($this->auth) {
            if ($this->auth['type'] === "basic") {
                $config = array_merge($config, ['auth' => $this->auth['auth']]);
            } else if ($this->auth['type'] === "digest") {
                $this->auth['auth'][] = "digest";
                $config = array_merge($config, ['auth' => $this->auth['auth']]);
            } else {
                $token = $this->auth['token'];
                $config['headers']['Authorization'] = "Bearer $token";
            }
        }

        if (isset($additionalConfig['headers'])) {
            $config['headers'] = array_merge($config['headers'], $additionalConfig['headers']);
            unset($additionalConfig['headers']);
        }

        return array_merge($additionalConfig, $config);
    }

    public function request(string $method, ?array $data = [])
    {
        $options = count($data) > 0 ? ['json' => $data, 'headers' => ['Content-Type' => 'application/json']] : [];
        $options = $this->configBuilder($options);

        if ($this->isAsync) {
            return $this->client->requestAsync($method, $this->url, $options);
        }

        return $this->client->request($method, $this->url, $options);
    }

    public function withBasicAuth(string $username, string $password): self
    {
        $this->auth = ['auth' => [$username, $password], 'type' => 'basic'];

        return $this;
    }

    public function withDigestAuth(string $username, string $password): self
    {
        $this->auth = ['auth' => [$username, $password], 'type' => 'digest'];

        return $this;
    }

    public function withBearerAuth(string $token): self
    {
        $this->auth = ['auth' => $token, 'type' => 'bearer'];

        return $this;
    }

    public function withoutAuth(): self
    {
        $this->auth = null;
        return $this;
    }
}
