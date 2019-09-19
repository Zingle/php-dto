<?php

namespace ZingleCom\DTO\Model;

/**
 * Class PassThruDTO
 *
 * Simple DTO for passing along array values directly
 */
class PassThruDTO implements TransmittableInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var mixed
     */
    private $dependency;


    /**
     * PassThruDTO constructor.
     *
     * @param array $data
     * @param mixed $dependency
     */
    public function __construct(array $data, $dependency = null)
    {
        $this->data       = $data;
        $this->dependency = $dependency;
    }

    /**
     * @return mixed
     */
    public function getDependency()
    {
        return $this->dependency;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
