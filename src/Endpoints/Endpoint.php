<?php

namespace ReallyDope\Ghost\Endpoints;

use ReallyDope\Ghost\Collection;
use ReallyDope\Ghost\Contracts\Transporter;
use ReallyDope\Ghost\Member;
use ReallyDope\Ghost\Resource;

class Endpoint
{
    /**
     * @var array<string, \ReallyDope\Ghost\Resource>
     */
    protected $mapping = [
        'members' => Member::class,
    ];

    /**
     * Create a transportable endpoint instance with the given transporter.
     */
    public function __construct(
        protected readonly Transporter $transporter
    ) {}

    /**
     * Get the resource class string for the given resource type.
     */
    protected function getResourceClass(string $resourceType): string
    {
        return isset($this->mapping[$resourceType])
            ? $this->mapping[$resourceType]
            : Resource::class;
    }

    /**
     * Create a new resource for the given endpoint with the given attributes.
     */
    protected function createResource(string $resourceType, array $attributes): Resource
    {
        /** @var Resource */
        $class = $this->getResourceClass($resourceType);

        return $class::from($attributes[$resourceType][0]);
    }

    /**
     * Create a new resource collection for the given endpoint and array of
     * attributes.
     */
    protected function createResourceCollection(string $resourceType, array $data): Collection
    {
        $resources = [];

        foreach ($data[$resourceType] as $key => $attributes) {
            $class = $this->getResourceClass($resourceType);

            $resources[] = $class::from($attributes);
        }

        return Collection::from($resources, $data['meta'] ?? []);
    }
}
