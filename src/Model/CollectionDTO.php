<?php

namespace ZingleCom\DTO\Model;

use phootwork\collection\Map;

/**
 * Class CollectionDTO
 *
 * Convenience DTO for transforming and transmitting collections
 */
class CollectionDTO implements TransmittableInterface
{
    /**
     * @var array
     */
    private $collection;

    /**
     * @var string
     */
    private $dtoClass;

    /**
     * @var array
     */
    private $dependencies;


    /**
     * SimpleListDTO constructor.
     * @param iterable $collection
     * @param string   $dtoClass
     * @param array    $dependencies
     */
    public function __construct(iterable $collection, string $dtoClass, array $dependencies = [])
    {
        $this->collection   = $collection;
        $this->dtoClass     = $dtoClass;
        $this->dependencies = $dependencies;
    }

    /**
     * @return array
     */
    public function getObjects(): array
    {
        if ($this->collection instanceof Map) {
            $keys = $this->collection->keys();
            $values = $this->collection->values()->map(function ($object) {
                return $this->makeDto($object);
            });

            return array_combine($keys->toArray(), $values->toArray());
        }

        if ($this->isDoctrineCollection()) {
            return $this->collection->map(function ($object) {
                return $this->makeDto($object);
            })->toArray();
        }

        return array_map(function ($object) {
            return $this->makeDto($object);
        }, is_array($this->collection) ? $this->collection : iterator_to_array($this->collection));
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->getObjects();
    }

    /**
     * @param object $object
     *
     * @return TransmittableInterface
     */
    private function makeDto($object): TransmittableInterface
    {
        $class = $this->dtoClass;

        return new $class($object, ...$this->dependencies);
    }

    /**
     * @return bool
     */
    private function isDoctrineCollection(): bool
    {
        return class_exists(Doctrine\Common\Collections\Collection::class)
            && $this->collection instanceof Doctrine\Common\Collections\Collection;
    }
}
