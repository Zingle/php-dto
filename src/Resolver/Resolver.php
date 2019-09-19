<?php

namespace ZingleCom\DTO\Resolver;

use phootwork\collection\ArrayList;
use phootwork\collection\Collection;
use phootwork\collection\Map;
use phootwork\collection\Queue;
use ZingleCom\DTO\Model\TransmittableInterface;

/**
 * Class Resolver
 */
class Resolver
{
    /**
     * @param \ZingleCom\DTO\Model\TransmittableInterface $transmittable
     * @param bool                   $isHal
     *
     * @return array
     */
    public function resolve(TransmittableInterface $transmittable, bool $isHal = false): array
    {
        if ($transmittable instanceof ArrayList) {
            return $transmittable->map(function (TransmittableInterface $dto) use ($isHal) {
                return $this->resolve($dto, $isHal);
            });
        }

        $data = $transmittable->toArray();

        return $this->resolveArray($data, $isHal)->toArray();
    }

    /**
     * @param array $data
     * @param bool  $isHal
     *
     * @return Collection
     */
    private function resolveArray(array $data, bool $isHal): Collection
    {
        $isCollection = $this->isCollection($data);
        $keyQueue = new Queue(array_keys($data));
        $map      = new Map();
        while (null !== ($key = $keyQueue->poll())) {
            $value = $data[$key];

            if ($value instanceof TransmittableInterface) {
                // Support embedded DTOs
                $this->resolveEmbedded($value, $map, $keyQueue, $isHal, $isCollection, $key);
            } elseif ($value instanceof \DateTime) {
                // Support DateTime serialization
                $map->set($key, $value->format(\DateTime::ISO8601));
            } else {
                $map->set($key, $value);
            }
        }

        return $map;
    }

    /**
     * @param TransmittableInterface $dto
     * @param Map                    $map
     * @param Queue                  $keyQueue
     * @param bool                   $isHal
     * @param bool                   $isCollection
     * @param mixed                  $key
     */
    private function resolveEmbedded(TransmittableInterface $dto, Map $map, Queue $keyQueue, bool $isHal, bool $isCollection, $key): void
    {
        $resolved = $this->resolve($dto, $isHal);

        // Merge when it's a mixed type array
        if (!is_int($key) || $isCollection) {
            $map->set($key, $resolved);
        } else {
            $map->setAll($resolved);
        }
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    private function isCollection(array $data): bool
    {
        foreach (array_keys($data) as $key) {
            if (!is_int($key)) {
                return false;
            }
        }

        return true;
    }
}
