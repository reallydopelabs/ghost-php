<?php

namespace ReallyDope\Ghost\ValueObjects\Transporter;

use ReallyDope\Ghost\Enums\Transporter\ContentType;
use ReallyDope\Ghost\ValueObjects\ApiKey;

final class Headers
{
    /**
     * Create a new Headers value object.
     *
     * @param array<string, string> $headers
     */
    public function __construct(
        private readonly array $headers
    ) {
        //
    }

    /**
     * Create a new Headers value object with the given API key.
     */
    public static function withAuthorization(ApiKey $apiKey): self
    {
        return new self([
            'Authorization' => "Ghost {$apiKey->toJwt()}",
        ]);
    }

    /**
     * Create a new Headers value object with the given user agent and existing headers.
     */
    public function withUserAgent(string $name, string $version): self
    {
        return new self([
            ...$this->headers,
            'User-Agent' => $name . '/' . $version,
        ]);
    }

    /**
     * Create a new Headers value object with the given content type and existing headers.
     */
    public function withContentType(ContentType $contentType, string $suffix = ''): self
    {
        return new self([
            ...$this->headers,
            'Content-Type' => $contentType->value . $suffix,
        ]);
    }

    /**
     * Create a new Headers value object with the given Ghost API version.
     */
    public function withGhostApiVersion(string $version): self
    {
        return new self([
            ...$this->headers,
            'Accept-Version' => "v{$version}",
        ]);
    }

    /**
     * Return the headers as an array.
     */
    public function toArray(): array
    {
        return $this->headers;
    }
}
