<?php

namespace ReallyDope\Ghost\ValueObjects;

use Firebase\JWT\JWT;
use ReallyDope\Ghost\Contracts\Stringable;

class ApiKey implements Stringable
{
    /**
     * Create a new API Key value object.
     */
    public function __construct(
        public readonly string $apiKey
    ) {
        //
    }

    /**
     * Create a new API Key value object from the given API Key.
     */
    public static function from(string $apiKey): self
    {
        return new self($apiKey);
    }

    /**
     * Returns the string representation of the object.
     */
    public function toString(): string
    {
        return $this->apiKey;
    }

    /**
     * Return a JWT-encoded API Key.
     */
    public function toJwt(): string
    {
        list($keyId, $secret) = explode(':', $this->toString());

        $now = time();

        return JWT::encode([
            'exp' => $now + 150,
            'iat' => $now,
            'aud' => '/admin/',
        ], pack('H*', $secret), 'HS256', $keyId);
    }
}
