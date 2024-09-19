<?php

namespace ReallyDope\Ghost\ValueObjects;

use ReallyDope\Ghost\Contracts\Stringable;

final class ResourceUri implements Stringable
{
    /**
     * Create a new Resource URI value object.
     */
    public function __construct(
        private readonly string $uri
    ) {
        //
    }

    /**
     * Create a new Resource URI value object that creates the given resource.
     */
    public static function create(string $resource): self
    {
        return new self($resource);
    }

    /**
     * Create a new Resource URI value object that lists the given resource.
     */
    public static function browse(string $resource, array $query = []): self
    {
        $query = http_build_query($query);

        return new self("{$resource}/?{$query}");
    }

    /**
     * Create a new Resource URI value object that retrieves the given resource.
     */
    public static function get(string $resource, string $id): self
    {
        return new self("{$resource}/{$id}/");
    }

    /**
     * Create a new Resource URI value object that updates the given resource.
     */
    public static function update(string $resource, string $id): self
    {
        return new self("{$resource}/{$id}/");
    }

    /**
     * Returns the string representation of the object.
     */
    public function toString(): string
    {
        return $this->uri;
    }
}
