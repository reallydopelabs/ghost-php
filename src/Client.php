<?php

namespace ReallyDope\Ghost;

use ReallyDope\Ghost\Contracts\Transporter;
use ReallyDope\Ghost\Endpoints\Endpoint;
use ReallyDope\Ghost\Endpoints\EndpointFactory;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client
{
    /**
     * The endpoint factory instance.
     */
    protected EndpointFactory $endpointFactory;

    /**
     * Create a new Client instance with the given transporter.
     */
    public function __construct(
        private readonly Transporter $transporter
    ) {}

    /**
     * Magic method to retrieve an endpoint by name.
     */
    public function __get(string $name)
    {
        return $this->getEndpoint($name);
    }

    /**
     * Retrieve the given endpoint from the factory.
     */
    public function getEndpoint(string $name): ?Endpoint
    {
        if (! isset($this->endpointFactory)) {
            $this->endpointFactory = new EndpointFactory($this->transporter);
        }

        return $this->endpointFactory->getEndpoint($name);
    }
}
