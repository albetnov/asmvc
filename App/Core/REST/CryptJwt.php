<?php

namespace App\Asmvc\Core\REST;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CryptJwt
{
    private string $hash, $type = HashType::HS384->value;
    private ?string $issuer = null, $audience = null;
    private ?int $issuedAt = null, $notBefore = null, $expiresAt = null;

    public function __construct()
    {
        $this->hash = env("APP_KEY", null);
        if (!$this->hash) {
            throw new KeyInvalidException();
        }
    }

    public function setHashType(HashType $hashType): self
    {
        $this->type = $hashType->value;
        return $this;
    }

    public function setIssuer(string $issuer): self
    {
        $this->issuer = $issuer;
        return $this;
    }

    public function setAudience(string $user): self
    {
        $this->audience = $user;
        return $this;
    }

    public function setIssuedAt(UnixTimestamp $issuedAt)
    {
        $this->issuedAt = $issuedAt->get();
        return $this;
    }

    public function setNotBefore(UnixTimestamp $notBefore)
    {
        $this->notBefore = $notBefore->get();
        return $this;
    }

    public function setExpiresAt(UnixTimestamp $expiresAt)
    {
        $this->expiresAt = $expiresAt->get();
        return $this;
    }

    private function parseData(array $data)
    {
        $initial = [];
        if ($this->issuer) $initial['iss'] = $this->issuer;
        if ($this->audience) $initial['aud'] = $this->audience;
        if ($this->issuedAt) $initial['iat'] = $this->issuedAt;
        if ($this->notBefore) $initial['nbf'] = $this->notBefore;
        if ($this->expiresAt) $initial['exp'] = $this->expiresAt;

        return array_merge($initial, $data);
    }

    public function encrypt(array $data)
    {
        $data = $this->parseData($data);
        dd($data);
        return JWT::encode($data, $this->hash, $this->type);
    }

    public function decrypt(string $token)
    {
        return JWT::decode($token, new Key($this->hash, $this->type));
    }
}
