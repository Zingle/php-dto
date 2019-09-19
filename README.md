Data Transfer Objects
=====================

Simple library for creating and serializing DTO trees.

## Why?

Serializing objects to arrays to transmit then as other types of data is a pain. This 
library makes it easy to create objects that decorate objects and describe their
serialization behavior. Resolver will then walk the tree serializing as it goes, so 
you end up with a plain array. Pass it along to whatever you'd like to serialize the
format to at that point.

## Example

~~~ php

use ZingleCom\DTO\Resolver;
use ZingleCom\DTO\Transmittable;
use Acme\SomeModel;

// Imagine that SomeModel has exposed getters on properties we'd like to serialize
class SomeModelDTO implements Transmittable
{
    /**
     * @var SomeModel
     */
    private $someModel;
    
    /**
     * @param SomeModel $someModel
     */
    public function __construct(SomeModel $someModel)
    {
        $this->someModel = $someModel;
    }
    
    public function toArray(): array
    {
        return [
            'id' => $this->someModel->getId(),
            'name' => $this->someModel->getName(),
            // imagine we have a related dto and model too
            'relatedModel' => new RelatedModelDTO('id' => $this->someModel->getRelatedModel()),
        ];
    }
}

// ... later you can do 
// image $someModel is instantiated somewhere else
$resolver = new Resolver();
$data = $resolver->resolve(new SomeModelDTO($someModel)); 

// $data is now an array as described in DTOs, now we can serialize to whatever
$json = json_encode($data); // JSON for instance
~~~

# Contribute

Fork me and open a PR against master. We'll look at it.
