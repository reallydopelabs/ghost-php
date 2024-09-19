<?php

namespace ReallyDope\Ghost\Endpoints;

use ReallyDope\Ghost\Contracts\Transporter;

class EndpointFactory
{
    /**
     * A list of endpoint classes.
     *
     * @var array<string, string>
     */
    private static array $classMap = [
        'members' => Members::class,
    ];

    /**
     * A list of available endpoints.
     *
     * @var array<string, string>
     */
    private array $endpoints = [];

    /**
     * Create a new Endpoint Factory instance.
     */
    public function __construct(
        private readonly Transporter $transporter
    ) {
        //
    }

    /**
     * Get the given endpoint by name.
     */
    public function getEndpoint(string $name): ?Endpoint
    {
        $endpointClass = array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;

        if (! $endpointClass) {
            return null;
        }

        if (! array_key_exists($name, $this->endpoints)) {
            $this->endpoints[$name] = new $endpointClass($this->transporter);
        }

        return $this->endpoints[$name];
    }
}
