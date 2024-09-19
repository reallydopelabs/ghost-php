<?php

namespace ReallyDope\Ghost\ValueObjects\Transporter;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use ReallyDope\Ghost\Enums\Transporter\ContentType;
use ReallyDope\Ghost\Enums\Transporter\Method;
use ReallyDope\Ghost\Ghost;
use ReallyDope\Ghost\ValueObjects\ResourceUri;

final class Payload
{
    /**
     * Create a new Transporter Payload instance.
     */
    public function __construct(
        private readonly ContentType $contentType,
        private readonly Method $method,
        private readonly ResourceUri $uri,
        private readonly array $parameters = [],
        private readonly array $query = [],
    ) {
        //
    }

    /**
     * Create a new Transporter Payload instance.
     */
    public static function browse(string $resource, array $query = []): self
    {
        $contentType = ContentType::JSON;
        $method = Method::GET;
        $uri = ResourceUri::browse($resource, $query);

        return new self($contentType, $method, $uri);
    }

    /**
     * Create a new Transporter Payload instance.
     */
    public static function get(string $resource, string $id): self
    {
        $contentType = ContentType::JSON;
        $method = Method::GET;
        $uri = ResourceUri::get($resource, $id);

        return new self($contentType, $method, $uri);
    }

    /**
     * Create a new Transporter Payload instance.
     */
    public static function create(string $resource, array $parameters): self
    {
        $contentType = ContentType::JSON;
        $method = Method::POST;
        $uri = ResourceUri::create($resource);

        return new self($contentType, $method, $uri, $parameters);
    }

    /**
     * Create a new Transporter Payload instance.
     */
    public static function update(string $resource, string $id, array $parameters): self
    {
        $contentType = ContentType::JSON;
        $method = Method::PATCH;
        $uri = ResourceUri::update($resource, $id);

        return new self($contentType, $method, $uri, $parameters);
    }

    /**
     * Create a new Psr 7 Request instance from the Payload.
     */
    public function toRequest(BaseUri $baseUri, Headers $headers): RequestInterface
    {
        $body = null;

        $uri = $baseUri->toString() . $this->uri->toString();

        $headers = $headers->withUserAgent('ghost-php', Ghost::VERSION)
            ->withContentType($this->contentType);

        if ($this->method === Method::POST || $this->method === Method::PATCH || $this->method === Method::PUT) {
            $body = json_encode($this->parameters, JSON_THROW_ON_ERROR);
        }

        return new Request($this->method->value, $uri, $headers->toArray(), $body);
    }
}
