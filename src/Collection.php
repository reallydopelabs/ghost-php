<?php

namespace ReallyDope\Ghost;

use ArrayAccess;
use ArrayIterator;
use BadMethodCallException;
use IteratorAggregate;
use Traversable;

class Collection implements IteratorAggregate, ArrayAccess
{
    /**
     * Create a new Collection from the given array of resources and metadata.
     *
     * @param array<Resource> $resources
     */
    public function __construct(
        private array $resources,
        private array $metadata = [],
    ) {
        //
    }

    /**
     * Create a new Collection from the given array of resources and metadata.
     *
     * @param array<Resource> $resources
     */
    public static function from(array $resources, array $metadata = []): static
    {
        return new static($resources, $metadata);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->resources);
    }

    /**
     * Get the resource collection's metadata.
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return isset($this->resources[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): mixed
    {
        return $this->resources[$offset] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        throw new BadMethodCallException('Cannot set resource attributes.');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        throw new BadMethodCallException('Cannot unset resource attributes.');
    }
}
